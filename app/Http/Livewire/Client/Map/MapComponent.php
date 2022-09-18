<?php

namespace App\Http\Livewire\Client\Map;

use App\Models\Business;
use App\Models\Patent;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class MapComponent extends Component
{
    public $long, $lat, $geoJson, $type, $country, $classification, $patentJson, $isLoading = false;
    protected $filters = [];
    protected $patents = [];

    protected $listeners = ['country_updated' => 'updatedCountry', 'type_updated' => 'updatedType', 'classification_updated' => 'updatedClassification'];

    public function mount($type, $country, $classification)
    {
        $this->country = $country;
        $this->type = $type;
        $this->classification = $classification;
        if ($country != null) {
            if ($this->type == "business" && $this->classification != null) {
                $this->filters = Business::where(['country_id' => $country, 'industry_classification_id' => $this->classification])->get();
            } else {
                $this->filters = Business::where('country_id', $country)->get();
            }
        } else {
            if ($this->type == "business" && $this->classification != null) {
                $this->filters = Business::where(['industry_classification_id' => $this->classification])->get();
            } else {
                $this->filters = Business::get();
            }
        }
        $this->patents = Patent::get();
        $this->country = $country;
        $this->loadJsonData();
        $this->emit("loader_off");
    }

    public function updatedCountry($country)
    {
        $this->mount($this->type, $country, $this->classification);
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
            ini_set('memory_limit', '300M');
            foreach ($data->chunk(1000) as $businessRow) {
                foreach($businessRow as $business) {
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
    }

    public function render()
    {
        return view('livewire.client.map.map-component');
    }
}
