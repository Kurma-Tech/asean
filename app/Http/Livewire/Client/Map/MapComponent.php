<?php

namespace App\Http\Livewire\Client\Map;

use App\Models\Business;
use App\Models\Patent;
use App\Models\Journal;
use App\Models\Region;
use App\Models\IndustryClassification;
use App\Models\JournalCategory;
use App\Models\PatentCategory;
use App\Models\BusinessGroup;
use App\Models\BusinessType;
use App\Models\PatentKind;
use App\Models\PatentType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;


class MapComponent extends Component
{

    use WithPagination;

    public
        $search = '',
        $long,
        $lat,
        $isDensityMap = true,
        $results,
        $geoJson,
        $type,
        $countries = [],
        $per_page = 10,
        $country,
        $year,
        $classification,
        $business_group,
        $business_type,
        $patent_kind,
        $patent_type,
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
    
    protected function rules()
    {
        return [
            'type'     => 'required',
            'country'  => 'required',
        ];
    }

    protected $messages = [
        'type.required'      => 'Data Type is required.',
        'country.required'    => 'You must select country from drop down.'
    ];

    public function mount(){
        $this->countries = Cache::rememberForever('countries_with_ids', function () {
            return json_decode(DB::table('countries')->select('id', 'name')->where("status", "1")->get(), true);
        });
    }

    public function changeMap($value){
        $this->isDensityMap = $value;
        $this->emit("map_changed");
    }

    public function handleSearch()
    {
        $this->emit("loader_on");
        $this->filterData();
    }

    /* 
        Mapping Listeners to Handler Functions 
    */
    // public function mapHandleMapFirstLoad()
    // {
    //     // $this->type = "all";
    //     // $this->emit("loader_on");
    //     // $this->filterData($this->type, $this->country, $this->classification);
    // }
    /* 
        Mapping Listeners to Handler Functions End
    */

    public function filterSubmit(){
        $this->validate();
        $this->emit("loader_on");
        $this->filterData();
    }

    private function filterData()
    {
        ini_set('memory_limit', '-1');
        // DB::enableQueryLog();
        /* Search from searchKeywords */
        $searchValues = explode(" ", $this->searchValue); // List of search keywords

        if($this->type == "business"){
            $businessQuery =  DB::table('businesses')->select('id', 'lat', 'long', 'year', 'company_name', "region_id", 'group_id', 'business_type_id');

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
                        }else{
                            $businessQuery = $businessQuery->where('company_name', 'LIKE', '%' . $searchValue . '%');
                        }
                        $tempOperation = "AND";
                    }
                }
            }

            if ($this->country != null) {
                $businessQuery = $businessQuery->where('country_id', $this->country);
            }
            if ($this->year != null || $this->year != "") {
                $businessQuery = $businessQuery->where('year', $this->year);
            }
            if ($this->business_group != null) {
                $businessQuery = $businessQuery->where('group_id', $this->country);
            }
            if ($this->business_type != null) {
                $businessQuery = $businessQuery->where('business_type_id', $this->country);
            }
            if ($this->classification != null) {
                $businessQuery = $businessQuery->where('industry_classification_id', $this->classification);
            }

            $this->totalBusiness = $businessQuery->count();
            $this->densityBusiness = $businessQuery->get();
            $this->business = $this->densityBusiness->chunk(5000);
            
        }
        
        if($this->type == "patent"){
            $patentQuery =  DB::table('patents')->select('id', 'lat', 'long', 'year', 'title', 'kind_id', 'type_id');

            $tempOperation = "AND";
            if ($this->searchValue != "") {
                foreach ($searchValues as $searchValue){
                    if($searchValue == "AND"){
    
                    }else if($searchValue == "OR"){
                        $tempOperation = "OR";
                    }else{
                        if($tempOperation == "OR"){
                            $patentQuery = $patentQuery->orWhere('title', 'LIKE', '%' . $searchValue . '%');
                        }else{
                            $patentQuery = $patentQuery->where('title', 'LIKE', '%' . $searchValue . '%');
                        }
                        $tempOperation = "AND";
                    }
                }
            }

            if ($this->country != null) {
                $patentQuery = $patentQuery->where('country_id', $this->country);
            }
            if ($this->year != null || $this->year != "") {
                $patentQuery = $patentQuery->where('year', $this->year);
            }
            if ($this->patent_kind != null) {
                $patentQuery = $patentQuery->where('kind_id', $this->country);
            }
            if ($this->patent_type != null) {
                $patentQuery = $patentQuery->where('type_id', $this->country);
            }
            if ($this->classification != null) {
                $listOfCategories = $this->classification;
                $patentQuery = $patentQuery->whereExists(function ($query) use ($listOfCategories) {
                    $query->select(DB::raw(1))
                          ->from('patent_pivot_patent_category')
                          ->where('parent_classification_id', $listOfCategories);
                });
            }

            $this->totalPatents = $patentQuery->count();
            $this->patents = $patentQuery->get();
        }

        if($this->type == "journal"){
            $journalQuery =  DB::table('journals')->select('id', 'lat', 'long', 'title', 'yaer');

            $tempOperation = "AND";
            if ($this->searchValue != "") {
                foreach ($searchValues as $searchValue){
                    if($searchValue == "AND"){
    
                    }else if($searchValue == "OR"){
                        $tempOperation = "OR";
                    }else{
                        if($tempOperation == "OR"){
                            $journalQuery = $journalQuery->orWhere('title', 'LIKE', '%' . $searchValue . '%');
                        }else{
                            $journalQuery = $journalQuery->where('title', 'LIKE', '%' . $searchValue . '%');
                        }
                        $tempOperation = "AND";
                    }
                }
            }

            if ($this->country != null) {
                $journalQuery = $journalQuery->where('country_id', $this->country);
            }
            if ($this->year != null || $this->year != "") {
                $journalQuery = $journalQuery->where('year', $this->year);
            }
            if ($this->classification != null) {
                $listOfCategories = $this->classification;
                $journalQuery = $journalQuery->whereExists(function ($query) use ($listOfCategories) {
                    $query->select(DB::raw(1))
                          ->from('journal_pivot_journal_category')
                          ->where('parent_classification_id', $listOfCategories);
                });
            }

            $this->totalJournals = $journalQuery->count();
            $this->journals = $journalQuery->get();
        }

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
        $categories = [];
        $business_types = [];
        $business_groups = [];
        $patent_kinds = [];
        $patent_types = [];
        $years = [];
        if ($this->type == "business") {
            $categories = Cache::rememberForever('industry_classifications_with_ids', function () {
                return IndustryClassification::select('id', 'classifications')->where('parent_id', '!=', null)->where('classifications', '!=', null)->where('section_id', '!=', null)->where('division_id', '!=', null)->where('group_id', '!=', null)->get();
            });
            $business_groups = Cache::rememberForever('business_groups', function () {
                return BusinessGroup::select('id', 'group')->where('group', '!=', null)->get();
            });
            $business_types = Cache::rememberForever('business_types', function () {
                return BusinessType::select('id', 'type')->where('type', '!=', null)->get();
            });
            $years = Cache::remember('business_year', 2592000  , function () {
                return DB::select(DB::raw("SELECT DISTINCT year FROM businesses ORDER BY year DESC"));
            });
        }elseif($this->type == "patent") {
            $categories = Cache::rememberForever('patent_classifications_with_ids', function () {
                return PatentCategory::select('id', 'classification_category')->where('class_id', null)->where('division_id', '!=', Null)->get();
            });
            $patent_kinds = Cache::rememberForever('patents_kinds', function () {
                return PatentKind::select('id', 'kind')->where('kind', '!=', null)->get();
            });
            $patent_types = Cache::rememberForever('patents_types', function () {
                return PatentType::select('id', 'type')->where('type', '!=', null)->get();
            });
            $years = Cache::remember('patent_year', 2592000  , function () {
                return DB::select(DB::raw("SELECT DISTINCT year FROM patents ORDER BY year DESC"));
            });
        }elseif($this->type == "journal") {
            $categories = Cache::rememberForever('journal_classifications_with_ids', function () {
                return JournalCategory::select('id', 'category')->where('division_id', null)->where('section_id', '!=', Null)->get();
            });
            $years = Cache::remember('journal_year', 2592000  , function () {
                return DB::select(DB::raw("SELECT DISTINCT year FROM journals ORDER BY year DESC"));
            });
        }

 
        return view('livewire.client.map.map-component', [
            'classifications'  => $categories,
            'business_groups'  => $business_groups,
            'business_types'   => $business_types,
            'patent_kinds'    => $patent_kinds,
            'patent_types'    => $patent_types,
            'years'           => $years
        ])->layout('layouts.client');
    }
}
