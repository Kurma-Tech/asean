<?php

namespace App\Http\Livewire\Client\Report;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Country;
use App\Models\IndustryClassification;
use App\Models\JournalCategory;
use App\Models\PatentKind;
use GuzzleHttp\Client;

class ReportComponent extends Component
{
    public
        $chartBusinessCount,
        $chartPatentsCount,
        $chartJournalsCount,
        $country,
        $classification,
        $forecastClassification,
        $forecastJournalClassification,
        $topLimitBusiness = 10,
        $topLimitPatent = 10,
        $topLimitJournal = 10,
        $isFirstLoad = true,
        $popularCountryBusiness,
        $popularCountryPatent, 
        $popularCountryJournals,
        $emergingCountryIndustry,
        $forecastCountry,
        $forecastPatentCountry,
        $forecastJournalCountry;

    protected
        $business = [],
        $patents = [],
        $journals = [];

    protected $listeners = [
        'reportFirstLoad' => 'reportHandleFirstLoad',
    ];

    public function reportHandleFirstLoad()
    {
        $this->filterData();
    }

    public function updatedCountry($country)
    {
        $this->country = $country;
        $this->filterData();
    }

    public function updatedForecastCountry($country)
    {
        $this->forecastCountry = $country;
        $this->updateForecastChart();
    }

    public function updatedForecastPatentCountry($country)
    {
        $this->forecastCountry = $country;
        $this->updateForecastPatentChart();
    }

    public function updatedForecastJournalCountry($country)
    {
        $this->forecastCountry = $country;
        $this->updateForecastJournalChart();
    }

    public function updatedEmergingCountryIndustry($country)
    {
        $this->emergingCountryIndustry = $country;
        $this->updateTopBusinessRate();
    }

    public function updatedPopularCountryBusiness($country)
    {
        $this->popularCountryBusiness = $country;
        $this->updateTopBusiness();
    }

    public function updatedPopularCountryPatent($country)
    {
        $this->popularCountryPatent = $country;
        $this->updateTopPatent();
    }

    public function updatedForecastClassification($classification)
    {
        $this->forecastClassification = $classification;
        $this->updateForecastChart();
    }

    public function updatedforecastJournalClassification($classification)
    {
        $this->forecastJournalClassification = $classification;
        $this->updateForecastJournalChart();
    }

    public function updatedTopLimitBusiness($topLimitBusiness)
    {
        $this->topLimitBusiness = $topLimitBusiness;
        $this->updateTopBusiness();
    }

    public function updatedTopLimitPatent($topLimitPatent)
    {
        $this->topLimitPatent = $topLimitPatent;
        $this->updateTopPatent();
    }

    // public function updatedTopLimitJournal($topLimitJournal)
    // {
    //     $this->topLimitJournal = $topLimitJournal;
    //     $this->updateTopJournal();
    // }

    public function updateTopBusiness()
    {
        ini_set('memory_limit', '-1');
        $businessQuery =  DB::table('businesses')->select('id', 'year', 'date_registered', 'industry_classification_id');

        if(!is_null($this->popularCountryBusiness) && $this->popularCountryBusiness != "")
        {
            $businessQuery = $businessQuery->where('country_id', $this->popularCountryBusiness);
        }

        $business = $businessQuery->get();

        $emergingBusinessData = [];

        $emergingBusiness = collect($business)->pluck('industry_classification_id')->countBy()->sortByDesc(null)->take($this->topLimitBusiness);

        foreach ($emergingBusiness as $key => $value) {
            array_push($emergingBusinessData, [
                "key" => IndustryClassification::find($key)->classifications,
                "value" => $value
            ]);
        }

        $this->emit("updateTopBusiness", [
            "emergingBusiness" => $emergingBusinessData
        ]);
    }

    public function updateTopBusinessRate()
    {
        ini_set('memory_limit', '-1');
        
        if(!is_null($this->emergingCountryIndustry) && $this->emergingCountryIndustry != "")
        {
            $test2 = collect(DB::table('businesses')->select('id', 'year', 'country_id', 'parent_classification_id')->get())->where('country_id', $this->emergingCountryIndustry)->pluck('parent_classification_id')->countBy();
        }else{
            $test2 = collect(DB::table('businesses')->select('id', 'year', 'parent_classification_id')->get())->pluck('parent_classification_id')->countBy();
        }
        
        $final = [];
        foreach ($test2 as $classKey => $value) {
            if ($classKey == null){
                continue;
            }else{
                $years = collect(DB::table('businesses')->select('id', 'year', 'parent_classification_id')->where('parent_classification_id', $classKey)->get())->pluck('year')->countBy();
                // dd($years);
                $rate = 0;
                $addition = 0;
                $temp = null;
                foreach ($years as $key => $value) {
                    if($temp != null){
                        $rate = $rate + (((int)$value - (int)$temp)/(int)$value) * 100;
                        $addition = $addition + 1;
                    }else{
                        $temp = $value;
                    }
                }
                $industryClassification = IndustryClassification::find($classKey);
                if($industryClassification != null){
                    array_push($final, [
                        "key" => $industryClassification->classifications,
                        "value" => round($rate / $addition,2)
                    ]);
                }
            }
        }
        
        // foreach ($test as $key => $value){
        //     $classification = IndustryClassification::find($key);
        //     if($test2->has($key) && !is_null($classification)){
        //         array_push($final, [
        //             "key" => IndustryClassification::find($key)->classifications,
        //             "value" => (((int)$value - (int)$test2[$key])/(int)$value) * 100
        //         ]);
        //     }else{
        //         continue;
        //     }
        // }

        rsort($final);

        $this->emit("emergingBusinessRate", [
            "emergingRate" => $final
        ]);
    }

    public function updateTopPatent()
    {
        ini_set('memory_limit', '-1');
        $patentQuery =  DB::table('patents')->select('id', 'registration_date', 'kind_id');

        if(!is_null($this->popularCountryPatent) && $this->popularCountryPatent != "")
        {
            $patentQuery = $patentQuery->where('country_id', $this->popularCountryPatent);
        }

        $patents = $patentQuery->get();

        $emergingPatentData = [];

        $emergingPatents = collect($patents)->pluck('kind_id')->countBy()->sortByDesc(null)->take(10);

        foreach ($emergingPatents as $key => $value) {
            array_push($emergingPatentData, [
                "key" => PatentKind::find($key)->kind,
                "value" => $value
            ]);
        }

        $this->emit("updateTopPatent", [
            "emergingPatents" => $emergingPatentData
        ]);
    }

    // public function updateTopJournal()
    // {
    //     ini_set('memory_limit', '-1');
    //     $patentQuery =  DB::table('journals')->select('id', 'registration_date', 'kind_id');

    //     if(!is_null($this->popularCountryJournals))
    //     {
    //         $patentQuery = $patentQuery->where('country_id', $this->popularCountryJournals);
    //     }

    //     $patents = $patentQuery->get();

    //     $emergingPatentData = [];

    //     $emergingPatents = collect($patents)->pluck('kind_id')->countBy()->sortByDesc(null)->take(10);

    //     foreach ($emergingPatents as $key => $value) {
    //         array_push($emergingPatentData, [
    //             "key" => PatentKind::find($key)->kind,
    //             "value" => $value
    //         ]);
    //     }

    //     $this->emit("updateTopPatent", [
    //         "emergingPatents" => $emergingPatentData
    //     ]);
    // }

    public function updateForecastChart(){
        $tempForcastData = $this->predict();

        $this->emit("reportsUpdated", [
            "forecastedFrom" =>  10,
            "forcastDates" => $tempForcastData["forecastedDates"],
            "forcastData" => $tempForcastData["forecastedData"],
            "forecastGraphLimit" => $tempForcastData["forecastGraphLimit"]
        ]);
    }

    public function updateForecastPatentChart(){
        $tempForcastPatentData = $this->predictPatent();

        $this->emit("reportsPatentUpdated", [
            "forecastedFrom" =>  10,
            "forcastPatentDates" => $tempForcastPatentData["forecastedPatentDates"],
            "forcastPatentData" => $tempForcastPatentData["forecastedPatentData"],
            "forecastPatentGraphLimit" => $tempForcastPatentData["forecastPatentGraphLimit"],
        ]);
    }

    public function updateForecastJournalChart(){
        $tempForcastJournalData = $this->predictJournal();

        $this->emit("reportsJournalUpdated", [
            "forecastedFrom" =>  10,
            "forcastJournalDates" => $tempForcastJournalData["forecastedJournalDates"],
            "forcastJournalData" => $tempForcastJournalData["forecastedJournalData"],
            "forecastJournalGraphLimit" => $tempForcastJournalData["forecastJournalGraphLimit"],
        ]);
    }

    public function filterData()
    {
        ini_set('memory_limit', '-1');
        $businessQuery =  DB::table('businesses')->select('id', 'year', 'date_registered', 'industry_classification_id');
        $patentQuery =  DB::table('patents')->select('id', 'registration_date', 'kind_id');
        $journalQuery =  DB::table('journals')->select('id', 'year');


        if ($this->country != null) {
            if ($this->classification != null) {
                $businessQuery = $businessQuery->where('country_id', $this->country)->where('industry_classification_id', $this->classification);
            } else {
                $businessQuery = $businessQuery->where('country_id', $this->country);
            }
            $patentQuery = $patentQuery->where('country_id', $this->country);
            $journalQuery = $journalQuery->where('country_id', $this->country);
        } else {
            if ($this->classification != null) {
                $businessQuery = $businessQuery->where('industry_classification_id', $this->classification);
            }
        }

        /* Get Query Data */
        $business = $businessQuery->get();
        $patents = $patentQuery->get();
        $journals = $journalQuery->get();
        /* Get Query Data End */


        $test2 = collect(DB::table('businesses')->select('id', 'year', 'parent_classification_id')->get())->pluck('parent_classification_id')->countBy();
        
        $final = [];
        foreach ($test2 as $classKey => $value) {
            if ($classKey == null){
                continue;
            }else{
                $years = collect(DB::table('businesses')->select('id', 'year', 'parent_classification_id')->where('parent_classification_id', $classKey)->get())->pluck('year')->countBy();
                // dd($years);
                $rate = 0;
                $addition = 0;
                $temp = null;
                foreach ($years as $key => $value) {
                    if($temp != null){
                        $rate = $rate + (((int)$value - (int)$temp)/(int)$value) * 100;
                        $addition = $addition + 1;
                    }else{
                        $temp = $value;
                    }
                }
                $industryClassification = IndustryClassification::find($classKey);
                if($industryClassification != null){
                    array_push($final, [
                        "key" => $industryClassification->classifications,
                        "value" => round($rate / $addition,2)
                    ]);
                }
            }
        }
        rsort($final);

        /* Default data for Charts */

        $this->chartBusinessCount = collect($business)->pluck('year')->countBy(); // business chart count

        $emergingBusinessData = [];

        $emergingBusiness = collect($business)->pluck('industry_classification_id')->countBy()->sortByDesc(null)->take(10);

        foreach ($emergingBusiness as $key => $value) {
            array_push($emergingBusinessData, [
                "key" => IndustryClassification::find($key)->classifications ?? Null,
                "value" => $value
            ]);
        }

        $emergingPatentData = [];

        $emergingPatents = collect($patents)->pluck('kind_id')->countBy()->sortByDesc(null)->take(10);

        foreach ($emergingPatents as $key => $value) {
            array_push($emergingPatentData, [
                "key" => PatentKind::find($key)->kind,
                "value" => $value
            ]);
        }

        $this->chartPatentsCount = collect($patents)->pluck('date')->countBy(function ($date) {
            $tempDate = substr(strchr($date, "/", 0), 4);
            if(strlen($tempDate) == 4){
                return $tempDate;
            }else{
                return false;
            }
        }); // Count of filtered patents with year extraction

        $this->chartJournalsCount = collect($journals)->pluck('year')->countBy();
        // dd();

        /* Default data for Charts End*/
        $lineChartYears = array_unique($this->chartBusinessCount->keys()->concat($this->chartPatentsCount->keys()->concat($this->chartJournalsCount->keys()))->toArray());
        sort($lineChartYears);
        $lineChartYears = array_values(array_diff($lineChartYears,[0]));
        // dd($lineChartYears);
        $tempChartPatentsCount = [];
        for ($i = 0; $i < count($lineChartYears); $i++) {
            try {
                if ($this->chartPatentsCount->has($lineChartYears[$i])) {
                    $tempChartPatentsCount[$lineChartYears[$i]] = $this->chartPatentsCount[$lineChartYears[$i]];
                } else {
                    $tempChartPatentsCount[$lineChartYears[$i]] = null;
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        $tempChartJournalsCount = [];
        for ($i = 0; $i < count($lineChartYears); $i++) {
            try {
                if ($this->chartJournalsCount->has($lineChartYears[$i])) {
                    $tempChartJournalsCount[$lineChartYears[$i]] = $this->chartJournalsCount[$lineChartYears[$i]];
                } else {
                    $tempChartJournalsCount[$lineChartYears[$i]] = null;
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        $tempChartBusinessCount = [];
        for ($i = 0; $i < count($lineChartYears); $i++) {
            try {
                if ($this->chartBusinessCount->has($lineChartYears[$i])) {
                    $tempChartBusinessCount[$lineChartYears[$i]] = $this->chartBusinessCount[$lineChartYears[$i]];
                } else if ($lineChartYears[$i] == "" || $lineChartYears[$i] == null) {
                } else {
                    $tempChartBusinessCount[$lineChartYears[$i]] = null;
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        //forecast
        // dd($tempChartBusinessCount);

        $tempForcastData = $this->predict();
        $tempForcastPatentData = $this->predictPatent();
        $tempForcastJournalData = $this->predictJournal();

        if ($this->isFirstLoad) {
            $this->emit("reportsFirstLoad", [
                "businessCount" => collect($business)->count(),
                "patentCount" => collect($patents)->count(),
                "journalCount" => collect($journals)->count(),
                "businessCountByYears" => collect($tempChartBusinessCount)->values(),
                "patentCountByYears" => collect($tempChartPatentsCount)->values(),
                "journalCountByYears" => collect($tempChartJournalsCount)->values(),
                "lineChartYears" => collect(($lineChartYears))->values(),
                "forecastedFrom" =>  10,
                "forcastDates" => $tempForcastData["forecastedDates"],
                "forcastData" => $tempForcastData["forecastedData"],
                "forecastGraphLimit" => $tempForcastData["forecastGraphLimit"],
                "forcastPatentDates" => $tempForcastPatentData["forecastedPatentDates"],
                "forcastPatentData" => $tempForcastPatentData["forecastedPatentData"],
                "forecastPatentGraphLimit" => $tempForcastPatentData["forecastPatentGraphLimit"],
                "forcastJournalDates" => $tempForcastJournalData["forecastedJournalDates"],
                "forcastJournalData" => $tempForcastJournalData["forecastedJournalData"],
                "forecastJournalGraphLimit" => $tempForcastJournalData["forecastJournalGraphLimit"],
                "emergingBusiness" => $emergingBusinessData,
                "emergingPatents" => $emergingPatentData,
                "emergingRate" => $final
            ]);
            $this->isFirstLoad = false;
        } else {
            $this->emit("reportsUpdated", [
                "businessCountByYears" => collect($tempChartBusinessCount)->values(),
                "patentCountByYears" => collect($tempChartPatentsCount)->values(),
                "journalCountByYears" => collect($tempChartJournalsCount)->values(),
                "lineChartYears" => collect($lineChartYears)->values(),
                "forecastedFrom" =>  $this->tempForcastData["forecastedDates"]->count() - collect($tempChartBusinessCount)->keys()->count(),
                "forcastDates" => $this->tempForcastData["forecastedDates"],
                "forcastData" => $this->tempForcastData["forecastedData"],
                "forecastGraphLimit" => $tempForcastData["forecastGraphLimit"],
                "forcastPatentDates" => $tempForcastPatentData["forecastedPatentDates"],
                "forcastPatentData" => $tempForcastPatentData["forecastedPatentData"],
                "forecastPatentGraphLimit" => $tempForcastPatentData["forecastPatentGraphLimit"],
                "forcastJournalDates" => $tempForcastJournalData["forecastedJournalDates"],
                "forcastJournalData" => $tempForcastJournalData["forecastedJournalData"],
                "forecastJournalGraphLimit" => $tempForcastJournalData["forecastJournalGraphLimit"],
            ]);
        }
    }

    public function predict()
    {
        $client = new Client();
        $res = $client->post('http://18.136.147.228/api/v1/predict', [
            'json' => [
                'country_id' => (!is_null($this->forecastCountry) && $this->forecastCountry != "") ? (int)$this->forecastCountry : null,
                'classification_id' => (!is_null($this->forecastClassification) && $this->forecastClassification != "") ? (int)$this->forecastClassification : null,
                'type' => "businesses"
            ]
        ]);
        $data = json_decode($res->getBody(), true); 
        // $res->getStatusCode();
        // 200
        // echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
        // echo ;
        // $p5 = (pow(1 + $r, 5)) * $pF;
        // dd($forecastedData);
        
        if($data["success"] == true){
            return ["forecastedDates" => $data['prediction_data']['keys'], "forecastedData" => $data['prediction_data']['values'], "forecastGraphLimit" => max($data['prediction_data']['values']) + 1000];
        }else{
            return ["forecastedDates" => ['2011-01-01', '2011-02-01'], "forecastedData" => [], "forecastGraphLimit" => 1000];
        }
        
    }

    public function predictPatent()
    {
        $client = new Client();
        $res = $client->post('http://18.136.147.228/api/v1/predict', [
            'json' => [
                'country_id' => (!is_null($this->forecastPatentCountry) && $this->forecastPatentCountry != "") ? (int)$this->forecastPatentCountry : null,
                'classification_id' => null,
                'type' => "patents"
            ]
        ]);
        $data = json_decode($res->getBody(), true); 
        
        if($data["success"] == true){
            return ["forecastedPatentDates" => $data['prediction_data']['keys'], "forecastedPatentData" => $data['prediction_data']['values'], "forecastPatentGraphLimit" => max($data['prediction_data']['values']) + 1000];
        }else{
            return ["forecastedPatentDates" => ['2011-01-01', '2011-02-01'], "forecastedPatentData" => [], "forecastPatentGraphLimit" => 1000];
        }
    }

    public function predictJournal()
    {
        $client = new Client();
        $res = $client->post('http://18.136.147.228/api/v1/predict', [
            'json' => [
                'country_id' => (!is_null($this->forecastJournalCountry) && $this->forecastJournalCountry != "") ? (int)$this->forecastJournalCountry : null,
                'classification_id' => (!is_null($this->forecastJournalClassification) && $this->forecastJournalClassification != "") ? (int)$this->forecastJournalClassification : null,
                'type' => "journal_pivot_journal_category"
            ]
        ]);
        $data = json_decode($res->getBody(), true); 
        
        if($data["success"] == true){
            return ["forecastedJournalDates" => $data['prediction_data']['keys'], "forecastedJournalData" => $data['prediction_data']['values'], "forecastJournalGraphLimit" => max($data['prediction_data']['values']) + 1000];
        }else{
            return ["forecastedJournalDates" => ['2011-01-01', '2011-02-01'], "forecastedJournalData" => [], "forecastJournalGraphLimit" => 1000];
        }
    }

    public function render()
    {
        // dd(DB::table('businesses')->select('id', 'year')->pluck('year')->countBy());
        $countries = Country::select('id', 'status', 'name')->where("status", "1")->get();
        $classifications = IndustryClassification::select('id', 'classifications')->where('parent_id', null)->where('classifications', '!=', null)->get();
        $journalClassifications = JournalCategory::select('id', 'category')->where('division_id', '==', null)->where('section_id', '!=', null)->get();
        return view('livewire.client.report.report-component', [
            'countries' => $countries,
            'classifications' => $classifications,
            'journalClassifications' => $journalClassifications,
            'allData' => DB::table('businesses')->select('id', 'year')->pluck('year')->countBy()
        ])->layout('layouts.client');
    }
}
