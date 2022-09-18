<?php

namespace App\Http\Livewire\Client\Map;

use App\Models\Business;
use App\Models\Patent;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class MapComponent extends Component
{
    public $long, $lat, $geoJson, $type, $country, $classification, $patentJson, $isLoading = false, $searchValue = '';
    protected $filters = [];
    protected $patents = [];

    protected $listeners = ['country_updated' => 'updatedCountry', 'type_updated' => 'updatedType', 'classification_updated' => 'updatedClassification', 'handleSearchEvent' => 'mapHandleSearchEvent'];

    public function mount($type, $country, $classification)
    {
        $this->country = $country;
        $this->type = $type;
        $this->classification = $classification;
        $searchValues = explode(" ",$this->searchValue);
        $businessQuery = Business::orderBy('company_name', 'ASC');
        foreach ($searchValues as $key => $searchValue) {
            $businessQuery = $businessQuery->where('company_name', 'LIKE', '%' . $searchValue . '%')->orWhere('ngc_code', 'LIKE', '%' . $searchValue . '%');
        }
        
        if ($country != null) {
            if ($this->type == "business" && $this->classification != null) {
                $businessQuery = $businessQuery->where(['country_id' => $country, 'industry_classification_id' => $this->classification]);
            } else {
                $businessQuery = $businessQuery->where('country_id', $country);
            }
        } else {
            if ($this->type == "business" && $this->classification != null) {
                $businessQuery = $businessQuery->where(['industry_classification_id' => $this->classification]);
            } else {
                $businessQuery = $businessQuery;
            }
        }
        $this->filters = $businessQuery->take(100)->get();
        $patentQuery = Patent::orderBy('id', 'ASC');
        foreach ($searchValues as $key => $searchValue) {
            $patentQuery = $patentQuery->where('title', 'LIKE', '%' . $searchValue . '%')->orWhere('patent_id', 'LIKE', '%' . $searchValue . '%');
        }
        $this->patents = $patentQuery->take(100)->get();
        $this->country = $country;
        $this->loadJsonData();
        $this->emit("loader_off");
    }

    public function updatedCountry($country)
    {
        $this->mount($this->type, $country, $this->classification);
    }

    public function mapHandleSearchEvent($search)
    {
        $this->searchValue = $search;
        $this->mount($this->type, $this->country, $this->classification);
    }

    public function updatedType($type)
    {
        $this->mount($type, $this->country, $this->classification);
    }

    public function updatedClassification($classification)
    {
        $this->mount($this->type, $this->country, $classification);
    }

    private function loadJsonData()
    {

        $data = [];
        $data = $this->filters;

        if ($this->type == "all" || $this->type == "business") {
            $businessData = [];

            foreach ($data as $business) {
                $businessData[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'coordinates' => [$business->long, $business->lat],
                        'type' => 'Point',
                    ],
                    'properties' => [
                        'locationId' => $business->id,
                        'company_name' => $business->company_name ?? 'No Data',
                        'date_registerd' => $business->date_registered ?? 'No Data',
                        'ngc_code' => $business->ngc_code ?? 'No Data',
                        'address' => $business->address ?? 'No Data',
                        'business_type' => $business->businessType->type ?? 'No Data',
                        'industry_classification' => $business->industryClassification->classifications ?? 'No Data',
                        'industry_description' => $business->industry_description ?? 'No Data',
                    ]
                ];
            }

            $geoLocations = [
                'type' => 'FeatureCollection',
                'features' => $businessData
            ];

            $geoJson = collect($geoLocations)->toJson();
            $this->geoJson = $geoJson;
        } else {
            $geoLocations = null;
        }


        if ($this->type == "all" || $this->type == "patent") {
            $patentData = [];

            foreach ($this->patents as $patent) {
                $patentData[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'coordinates' => [$patent->long, $patent->lat],
                        'type' => 'Point',
                    ],
                    'properties' => [
                        'id' => $patent->id,
                        'patent_id' => $patent->patent_id,
                        'title' => $patent->title ?? 'No Data',
                        'date_registerd' => $patent->date ?? 'No Data'
                    ]
                ];
            }

            $patentGeoLocations = [
                'type' => 'FeatureCollection',
                'features' => $patentData
            ];

            $patentJson = collect($patentGeoLocations)->toJson();
            $this->patentJson = $patentJson;
        } else {
            $patentGeoLocations = null;
        }
        // dd( count($businessData ?? []) + count($patentData ?? []));

        $this->emit("resultsUpdated", count($businessData ?? []) + count($patentData ?? []));

        $businessByYears = $data->pluck('year')->countBy();
        $patentByYears = $this->patents->pluck('date')->countBy(function ($date) {
            return substr(strchr($date, "-", -1), 0);
        });

        $lineChartYears = $businessByYears->keys()->concat($patentByYears->keys());

        $this->emit("reportsUpdated", ["businessCountByYears" => $businessByYears->values(), "patentCountByYears" => $patentByYears->values(), "lineChartYears" => $lineChartYears->sort()]);

        $this->emit("mapUpdated", ["geoJson" => $geoLocations, "patentJson" => $patentGeoLocations]);

        $this->emit("resultsDataUpdate", ['businessData' => $geoLocations, 'patentData' => $patentGeoLocations]);
    }

    public function render()
    {
        return view('livewire.client.map.map-component');
    }
}
