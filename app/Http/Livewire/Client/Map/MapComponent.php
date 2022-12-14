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
        'mapFirstLoad' => 'mapHandleMapFirstLoad',
        'country_updated' => 'updatedCountry',
        'type_updated' => 'updatedType',
        'classification_updated' => 'updatedClassification',
        'handleSearchEvent' => 'mapHandleSearchEvent'
    ];

    /* 
        Mapping Listeners to Handler Functions 
    */
    public function mapHandleMapFirstLoad()
    {
        $this->type = "all";
        $this->emit("loader_on");
        $this->filterData($this->type, $this->country, $this->classification);
    }

    public function updatedCountry($country)
    {
        Log::info($country);
        $this->filterData($this->type, $country, $this->classification);
    }

    public function mapHandleSearchEvent($search)
    {
        $this->searchValue = $search;
        $this->filterData($this->type, $this->country, $this->classification);
    }

    public function updatedType($type)
    {
        $this->filterData($type, $this->country, $this->classification);
    }

    public function updatedClassification($classification)
    {
        $this->filterData($this->type, $this->country, $classification);
    }
    /* 
        Mapping Listeners to Handler Functions End
    */

    private function filterData($type, $country, $classification)
    {
        ini_set('memory_limit', '-1');
        // Updating filter values
        $this->country = $country;
        $this->type = $type;
        $this->classification = $classification;

        /* Search from searchKeywords */
        $searchValues = explode(" ", $this->searchValue); // List of search keywords


        /* Model Queries */
        DB::enableQueryLog();
        $businessQuery =  DB::table('businesses')->select('id', 'lat', 'long', 'year', 'company_name');
        $patentQuery =  DB::table('patents')->select('id', 'lat', 'long', 'date');
        /* Model Queries End */


        /* Search from searchKeywords */
        // Searching business on company_name and ngc_code fields
        if ($this->searchValue != "") {
            foreach ($searchValues as $searchValue) {
                // $businessQuery = $businessQuery->where('company_name', 'LIKE', '%' . $searchValue . '%')->orWhere('ngc_code', 'LIKE', '%' . $searchValue . '%');
                $businessQuery = $businessQuery->where('company_name', 'LIKE', '%' . $searchValue . '%');
            }
        }

        // Searching patents on title and patent_id fields
        foreach ($searchValues as $searchValue) {
            // $patentQuery = $patentQuery->where('title', 'LIKE', '%' . $searchValue . '%')->orWhere('patent_id', 'LIKE', '%' . $searchValue . '%');
            $patentQuery = $patentQuery->where('title', 'LIKE', '%' . $searchValue . '%');
        }
        /* Search from searchKeywords End */


        /* Filter By Country and Classification */
        if ($country != null) {
            if ($this->type == "business" && $this->classification != null) {
                Log::info($this->classification);
                $businessQuery = $businessQuery->where('country_id', $country)->where('industry_classification_id', $this->classification);
            } else {
                $businessQuery = $businessQuery->where('country_id', $country);
            }
        } else {
            if ($this->type == "business" && $this->classification != null) {
                $businessQuery = $businessQuery->where('industry_classification_id', $this->classification);
            }
        }
        /* Filter By Country and Classification End*/

        /* Get Query Data */
        $this->business = $businessQuery->get()->chunk(5000);
        $this->patents = $patentQuery->get();
        /* Get Query Data End */

        $this->loadJsonData(); // Load data for map
        $this->emit("loader_off"); // Loader off
    }


    private function loadJsonData()
    {
        ini_set('memory_limit', '-1');
        /*  */
        if ($this->type == "all" || $this->type == "business") {
            $tempBusinessDataChunked = [];
            foreach ($this->business as $chunkedData) {
                $tempBusinessData = [];
                foreach ($chunkedData as $business) {
                    $tempBusinessData[] = [
                        'type' => 'Feature',
                        'geometry' => [
                            'coordinates' => [$business->long ?? 0, $business->lat ?? 0],
                            'type' => 'Point',
                        ],
                        'properties' => [
                            'locationId' => $business->id,
                            'company_name' => $business->company_name
                        ]
                    ];
                }
                $geoBusinessData = [
                    'type' => 'FeatureCollection',
                    'features' => $tempBusinessData
                ];
                array_push($tempBusinessDataChunked, $geoBusinessData);
            }
        } else {
            $tempBusinessDataChunked = [];
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

        $this->emit("mapUpdated", ["geoJson" => $tempBusinessDataChunked, "patentJson" => $patentGeoLocations]);
    }

    public function getBusinessDataFromId($id)
    {
        $business = Business::find($id);
        return [
            'locationId' => $business->id,
            'company_name' => $business->company_name ?? 'No Data',
            'date_registerd' => $business->date_registered ?? 'No Data',
            'ngc_code' => $business->ngc_code ?? 'No Data',
            'address' => $business->address ?? 'No Data',
            'business_type' => $business->businessType->type ?? 'No Data',
            'industry_classification' => $business->industryClassification->classifications ?? 'No Data',
            'industry_description' => $business->industry_description ?? 'No Data'
        ];
    }

    public function getPatentDataFromId($id)
    {
        $patent = Patent::find($id);
        return [
            'id' => $patent->id,
            'patent_id' => $patent->patent_id,
            'title' => $patent->title ?? 'No Data',
            'date_registerd' => $patent->date ?? 'No Data'
        ];
    }

    public function render()
    {
        return view('livewire.client.map.map-component');
    }
}
