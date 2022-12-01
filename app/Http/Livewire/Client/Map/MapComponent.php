<?php

namespace App\Http\Livewire\Client\Map;

use App\Models\Business;
use App\Models\Patent;
use App\Models\Journal;
use App\Models\Region;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MapComponent extends Component
{
    public
        $long,
        $lat,
        $isDensityMap = true,
        $geoJson,
        $type,
        $country,
        $classification,
        $patentJson,
        $isLoading = false,
        $searchValue = '',
        $chartBusinessCount,
        $chartPatentsCount,
        $totalBusiness = 0,
        $totalPatents = 0,
        $totalJournals = 0;

    protected
        $business = [],
        $densityBusiness = [],
        $patents = [],
        $journals = [];

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

    public function changeMap($value){
        $this->isDensityMap = $value;
        $this->emit("map_changed");
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
        log::info($classification);
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
        $businessQuery =  DB::table('businesses')->select('id', 'lat', 'long', 'year', 'company_name', "region_id");
        $patentQuery =  DB::table('patents')->select('id', 'lat', 'long', 'registration_date', 'title');
        $journalQuery =  DB::table('journals')->select('id', 'lat', 'long', 'title');
        /* Model Queries End */


        /* Search from searchKeywords */
        // Searching business on company_name and ngc_code fields
        $tempOperation = "AND";
        if ($this->searchValue != "") {
            foreach ($searchValues as $searchValue){
                // $businessQuery = $businessQuery->where('company_name', 'LIKE', '%' . $searchValue . '%')->orWhere('ngc_code', 'LIKE', '%' . $searchValue . '%');
                if($searchValue == "AND"){

                }else if($searchValue == "OR"){
                    $tempOperation = "OR";
                }else{
                    if($tempOperation == "OR"){
                        $businessQuery = $businessQuery->orWhere('company_name', 'LIKE', '%' . $searchValue . '%');
                        $patentQuery = $patentQuery->orWhere('title', 'LIKE', '%' . $searchValue . '%');
                        $journalQuery = $journalQuery->orWhere('title', 'LIKE', '%' . $searchValue . '%');
                    }else{
                        $businessQuery = $businessQuery->where('company_name', 'LIKE', '%' . $searchValue . '%');
                        $patentQuery = $patentQuery->where('title', 'LIKE', '%' . $searchValue . '%');
                        $journalQuery = $journalQuery->where('title', 'LIKE', '%' . $searchValue . '%');
                    }
                    $tempOperation = "AND";
                }
            }
        }

        // Searching patents on title and patent_id fields
        // foreach ($searchValues as $searchValue) {
            // $patentQuery = $patentQuery->where('title', 'LIKE', '%' . $searchValue . '%')->orWhere('patent_id', 'LIKE', '%' . $searchValue . '%');
            
        // }
        /* Search from searchKeywords End */


        /* Filter By Country and Classification */
        if ($country != null) {
            if ($this->type == "business" && $this->classification != null) {
                Log::info($this->classification);
                $businessQuery = $businessQuery->where('country_id', $country)->where('industry_classification_id', $this->classification);
            } else {
                $businessQuery = $businessQuery->where('country_id', $country);
            }
            if ($this->type == "patent" && $this->classification != null) {
                Log::info($this->classification);
                $listOfCategories = $this->classification;
                $patentQuery = $patentQuery->where('country_id', $country)->whereExists(function ($query) use ($listOfCategories) {
                    $query->select(DB::raw(1))
                          ->from('patent_categories')
                          ->where('patent_categories.id', $listOfCategories);
                });
                
                // ->with(['patentCategories' => function($query) use ($listOfCategories) {
                //     $query->where('patent_categories.id', $listOfCategories);
                // }]);
            } else {
                $patentQuery = $patentQuery->where('country_id', $country);
            }
            if ($this->type == "journals" && $this->classification != null) {
                Log::info($this->classification);
                $listOfCategories = $this->classification;
                $journalQuery = $journalQuery->where('country_id', $country)->whereExists(function ($query) use ($listOfCategories) {
                    $query->select(DB::raw(1))
                          ->from('journal_categories')
                          ->where('journal_categories.id', $listOfCategories);
                });
                // ->with(['journalCategories' => function($query) use ($listOfCategories) {
                //     $query->where('journal_categories.id', $listOfCategories);
                // }]);
            } else {
                $journalQuery = $journalQuery->where('country_id', $country);
            }
        } else {
            if ($this->type == "business" && $this->classification != null) {
                $businessQuery = $businessQuery->where('industry_classification_id', $this->classification);
            }

            if ($this->type == "patent" && $this->classification != null) {
                Log::info($this->classification);
                $listOfCategories = $this->classification;
                $patentQuery = $patentQuery->where('country_id', $country)->whereExists(function ($query) use ($listOfCategories) {
                    $query->select(DB::raw(1))
                          ->from('patent_categories')
                          ->where('patent_categories.id', $listOfCategories);
                });
            } 

            if ($this->type == "journals" && $this->classification != null) {
                Log::info($this->classification);
                $listOfCategories = $this->classification;
                $journalQuery = $journalQuery->where('country_id', $country)->whereExists(function ($query) use ($listOfCategories) {
                    $query->select(DB::raw(1))
                          ->from('journal_categories')
                          ->where('journal_categories.id', $listOfCategories);
                });
            }
        }
        /* Filter By Country and Classification End*/

        $this->totalBusiness = $businessQuery->count();
        $this->totalPatents = $patentQuery->count();
        $this->totalJournals = $journalQuery->count();

        /* Get Query Data */
        $this->business = $businessQuery->get()->chunk(5000);
        $this->densityBusiness = $businessQuery->get();
        $this->patents = $patentQuery->get();
        $this->journals = $journalQuery->get();
        /* Get Query Data End */

        $this->loadJsonData(); // Load data for map
        $this->loadDensityJsonData();
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
                            'company_name' => $business->company_name,
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
                        'company_name' => $patent->title,
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
            $patentGeoLocations = [
                'type' => 'FeatureCollection',
                'features' => []
            ];
        }

        if ($this->type == "all" || $this->type == "journal") {
            $journalData = [];
            foreach ($this->journals as $journal) {
                $journalData[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'coordinates' => [$journal->long, $journal->lat],
                        'type' => 'Point',
                    ],
                    'properties' => [
                        'id' => $journal->id,
                        'company_name' => $journal->title,
                    ]
                ];
            }
            $journalGeoLocations = [
                'type' => 'FeatureCollection',
                'features' => $journalData
            ];
        } else {
            $journalGeoLocations = [
                'type' => 'FeatureCollection',
                'features' => []
            ];
        }

        $this->emit("resultsUpdated", $this->totalBusiness + $this->totalPatents);

        $this->emit("mapUpdated", ["geoJson" => $tempBusinessDataChunked, "patentJson" => $patentGeoLocations, "journalJson" => $journalGeoLocations]);
    }

    private function loadDensityJsonData(){
        $densityBusinessData = [];
        foreach(collect($this->densityBusiness)->whereNotNull('region_id')->pluck('region_id')->countBy() as $key => $value)
        {
            array_push($densityBusinessData, [
                "name" => Region::find($key)->name,
                "count" => $value
            ]);
        }
        $this->emit("densityMapUpdated", ["densityBusinessData"=>$densityBusinessData]);
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
            'date_registerd' => $patent->registration_date ?? 'No Data'
        ];
    }

    public function getJournalDataFromId($id)
    {
        $journal = Journal::find($id);
        return [
            'id' => $journal->id,
            'title' => $journal->title ?? 'No Data',
            'abstract' => $journal->abstract ?? 'No Data',
            'author_name' => $journal->author_name ?? 'No Data',
            'publisher_name' => $journal->publisher_name ?? 'No Data',
            'issn_no' => $journal->issn_no ?? 'No Data',
            'year' => $journal->year ?? 'No Data',
            'citition_no' => $journal->citition_no ?? 'No Data',
        ];
    }

    public function render()
    {
        return view('livewire.client.map.map-component');
    }
}
