<?php

namespace App\Http\Livewire\Client\Map;

use App\Models\Business;
use App\Models\Patent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MapComponent extends Component
{
    public
        $long,
        $lat,
        $geoJson,
        $type,
        $country,
        $classification,
        $patentJson,
        $isLoading = false,
        $searchValue = '',
        $chartBusinessCount,
        $chartPatentsCount;

    protected
        $business = [],
        $patents = [];

    protected $listeners = [
        'country_updated' => 'updatedCountry',
        'type_updated' => 'updatedType',
        'classification_updated' => 'updatedClassification',
        'handleSearchEvent' => 'mapHandleSearchEvent'
    ];

    public function mount($type, $country, $classification)
    {
        // ini_set('memory_limit', '3000M');

        // Updating filter values
        $this->country = $country;
        $this->type = $type;
        $this->classification = $classification;


        /* Model Queries */
        // $businessQuery = Business::select([
        //     'id',
        //     // 'company_name',
        //     // 'ngc_code',
        //     // 'country_id',
        //     // 'industry_classification_id',
        //     // 'date_registered',
        //     // 'industry_description',
        //     'long',
        //     'lat', 
        //     'year'
        // ])->orderBy('id', 'ASC');
        $businessTest = DB::select('select `id`, `lat`, `long` from businesses WHERE `company_name` LIKE '."'%test%';",);
        $patentQuery = Patent::select([
            'id',
            // 'title',
            // 'patent_id',
            // 'country_id',
            'date',
            'long',
            'lat'
        ])->orderBy('id', 'ASC');
        /* Model Queries End */

        dd($businessTest);


        /* Search from searchKeywords */
        $searchValues = explode(" ", $this->searchValue); // List of search keywords

        // Searching business on company_name and ngc_code fields
        // foreach ($searchValues as $key => $searchValue) {
        //     $businessQuery = $businessQuery->where(function ($query) use ($searchValue) {
        //         $query->where('company_name', 'LIKE', '%' . $searchValue . '%')->orWhere('ngc_code', 'LIKE', '%' . $searchValue . '%');
        //     });
        // }

        // Searching patents on title and patent_id fields
        // foreach ($searchValues as $key => $searchValue) {
        //     $patentQuery = $patentQuery->where(function ($query) use ($searchValue) {
        //         $query->where('title', 'LIKE', '%' . $searchValue . '%')->orWhere('patent_id', 'LIKE', '%' . $searchValue . '%');
        //     });
        // }
        /* Search from searchKeywords End */


        /* Filter By Country and Classification */
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
        /* Filter By Country and Classification End*/


        /* Default data for Charts */
        $tempBusinessQuery = $businessQuery; // Temporary Business Query Variable
        $tempPatentQuery = $patentQuery; // Temporary Patent Query Variable
        $this->chartBusinessCount = $tempBusinessQuery->pluck('year')->countBy(); // business chart count

        $this->chartPatentsCount = $tempPatentQuery->pluck('date')->countBy(function ($date) {
            return substr(strchr($date, "-", -1), 0);
        }); // Count of filtered patents with year extraction

        /* Default data for Charts End*/


        /* Get Query Data */
        $this->business = $businessQuery->take(50000)->get()->chunk(1000);
        $this->patents = $patentQuery->take(50000)->get()->chunk(1000);
        /* Get Query Data End */


        $this->loadJsonData(); // Load data for map
        $this->emit("loader_off"); // Loader off
    }


    /* 
        Mapping Listeners to Handler Functions 
    */
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
    /* 
        Mapping Listeners to Handler Functions End
    */


    private function loadJsonData()
    {
        /*  */
        if ($this->type == "all" || $this->type == "business") {
            $tempBusinessData = [];
            
            foreach ($this->business as $key => $chunkedData) {
                foreach ($chunkedData as $business) {
                    $tempBusinessData[] = [
                        'type' => 'Feature',
                        'geometry' => [
                            'coordinates' => [$business->long ?? 0, $business->lat ?? 0],
                            'type' => 'Point',
                        ],
                        'properties' => [
                            'locationId' => $business->id,
                            // 'company_name' => $business->company_name ?? 'No Data',
                            // 'date_registerd' => $business->date_registered ?? 'No Data',
                            // 'ngc_code' => $business->ngc_code ?? 'No Data',
                            // 'address' => $business->address ?? 'No Data',
                            // 'business_type' => $business->businessType->type ?? 'No Data',
                            // 'industry_classification' => $business->industryClassification->classifications ?? 'No Data',
                            // 'industry_description' => $business->industry_description ?? 'No Data',
                        ]
                    ];
                }
            }
            $geoBusinessData = [
                'type' => 'FeatureCollection',
                'features' => $tempBusinessData
            ];
            $geoJson = collect($geoBusinessData)->toJson();
            $this->geoJson = $geoJson;
        } else {
            $geoBusinessData = null;
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
                        // 'patent_id' => $patent->patent_id,
                        // 'title' => $patent->title ?? 'No Data',
                        // 'date_registerd' => $patent->date ?? 'No Data'
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

        $this->emit("resultsUpdated", count($businessData ?? []) + count($patentData ?? []));

        $lineChartYears = $this->chartBusinessCount->keys()->concat($this->chartPatentsCount->keys());

        $this->emit("reportsUpdated", ["businessCountByYears" => $this->chartBusinessCount->values(), "patentCountByYears" => $this->chartPatentsCount->values(), "lineChartYears" => $lineChartYears->sort()]);

        $this->emit("mapUpdated", ["geoJson" => $geoBusinessData, "patentJson" => $patentGeoLocations]);

        $this->emit("resultsDataUpdate", ['businessData' => $geoBusinessData, 'patentData' => $patentGeoLocations]);
    }

    public function render()
    {
        return view('livewire.client.map.map-component');
    }
}
