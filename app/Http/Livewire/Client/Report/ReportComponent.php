<?php

namespace App\Http\Livewire\Client\Report;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Country;
use App\Models\IndustryClassification;
use App\Models\JournalCategory;
use App\Models\PatentCategory;
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
        $forecastPatentClassification,
        $topLimitBusiness = 10,
        $topLimitPatent = 10,
        $topLimitJournal = 10,
        $isFirstLoad = true,
        $popularCountryBusiness,
        $popularCountryPatent,
        $popularCountryJournals,
        $emergingCountryIndustry,
        $emergingYoungIndustry,
        $emergingCountryJournal,
        $emergingYoungJournal,
        $emergingCountryPatent,
        $emergingYoungPatent,
        $forecastCountry,
        $forecastPatentCountry,
        $topCountryFilter,
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

    public function updatedTopCountryFilter($country)
    {
        $this->topCountryFilter = $country;
        $this->updateTopChart();
    }

    public function updatedForecastCountry($country)
    {
        $this->forecastCountry = $country;
        $this->updateForecastChart();
    }

    public function updatedForecastPatentCountry($country)
    {
        $this->forecastPatentCountry = $country;
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

    public function updatedEmergingYoungIndustry($young)
    {
        $this->emergingYoungIndustry = $young;
        $this->updateTopBusinessRate();
    }

    public function updatedEmergingYoungJournal($young)
    {
        $this->emergingYoungJournal = $young;
        $this->updateTopJournalRate();
    }

    public function updatedEmergingYoungPatent($young)
    {
        $this->emergingYoungPatent = $young;
        $this->updateTopPatentRate();
    }

    public function updatedEmergingCountryJournal($country)
    {
        $this->emergingCountryJournal = $country;
        $this->updateTopJournalRate();
    }

    public function updatedEmergingCountryPatent($country)
    {
        $this->emergingCountryPatent = $country;
        $this->updateTopPatentRate();
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

    public function updatedPopularCountryJournals($country)
    {
        $this->popularCountryJournals = $country;
        $this->updateTopJournal();
    }

    public function updatedForecastClassification($classification)
    {
        $this->forecastClassification = $classification;
        $this->updateForecastChart();
    }

    public function updatedForecastJournalClassification($classification)
    {
        $this->forecastJournalClassification = $classification;
        $this->updateForecastJournalChart();
    }

    public function updatedForecastPatentClassification($classification)
    {
        $this->forecastPatentClassification = $classification;
        $this->updateForecastPatentChart();
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

    public function updatedTopLimitJournal($topLimitJournal)
    {
        $this->topLimitJournal = $topLimitJournal;
        $this->updateTopJournal();
    }

    public function updateTopBusiness()
    {
        ini_set('memory_limit', '-1');
        $businessQuery =  DB::table('businesses')->select('id', 'year', 'date_registered', 'industry_classification_id');

        if (!is_null($this->popularCountryBusiness) && $this->popularCountryBusiness != "") {
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

        if (!is_null($this->emergingCountryIndustry) && $this->emergingCountryIndustry != "") {
            $test2 = collect(DB::table('businesses')->select('id', 'year', 'country_id', 'parent_classification_id')->get())->where('country_id', $this->emergingCountryIndustry)->filter(function ($value, $key) {
                if ($value->year != null  && $this->emergingYoungIndustry != null && $this->emergingYoungIndustry != ""){
                    return (date('Y') - (int)$value->year) <= $this->emergingYoungIndustry;
                }else{
                    return true;
                }
                
            })->pluck('parent_classification_id')->countBy();
        } else {
            $test2 = collect(DB::table('businesses')->select('id', 'year', 'parent_classification_id')->get())->filter(function ($value, $key) {
                if ($value->year != null  && $this->emergingYoungIndustry != null && $this->emergingYoungIndustry != ""){
                    return (date('Y') - (int)$value->year) <= $this->emergingYoungIndustry;
                }else{
                    return true;
                }
                
            })->pluck('parent_classification_id')->countBy();
        }

        $final = [];
        foreach ($test2 as $classKey => $value) {
            if ($classKey == null) {
                continue;
            } else {
                $years = collect(DB::table('businesses')->select('id', 'year', 'parent_classification_id')->where('parent_classification_id', $classKey)->get())->filter(function ($value, $key) {
                    if ($value->year != null  && $this->emergingYoungIndustry != null && $this->emergingYoungIndustry != ""){
                        return (date('Y') - (int)$value->year) <= $this->emergingYoungIndustry;
                    }else{
                        return true;
                    }
                    
                })->pluck('year')->countBy();
                // dd($years);
                $rate = 0;
                $addition = 0;
                $temp = null;
                foreach ($years as $key => $value) {
                    if ($temp != null) {
                        $rate = $rate + (((int)$value - (int)$temp) / (int)$value) * 100;
                        $addition = $addition + 1;
                    } else {
                        $temp = $value;
                    }
                }
                $industryClassification = IndustryClassification::find($classKey);
                if ($industryClassification != null) {
                    array_push($final, [
                        "key" => $industryClassification->classifications,
                        "value" => ($addition == 0) ? 0 : round($rate / $addition, 2)
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

    public function updateTopJournalRate()
    {
        ini_set('memory_limit', '-1');

        if (!is_null($this->emergingCountryJournal) && $this->emergingCountryJournal != "") {
            $journalClassificationForEmerging = collect(DB::table('journal_pivot_journal_category')->select('id', 'year', 'parent_classification_id', 'country_id')->get())->where('country_id', $this->emergingCountryJournal)->filter(function ($value, $key) {
                if ($value->year != null  && $this->emergingYoungJournal != null && $this->emergingYoungJournal != ""){
                    return (date('Y') - (int)$value->year) <= $this->emergingYoungJournal;
                }else{
                    return true;
                }
                
            })->pluck('parent_classification_id')->countBy();
        } else {
            $journalClassificationForEmerging = collect(DB::table('journal_pivot_journal_category')->select('id', 'year', 'parent_classification_id')->get())->filter(function ($value, $key) {
                if ($value->year != null  && $this->emergingYoungJournal != null && $this->emergingYoungJournal != ""){
                    return (date('Y') - (int)$value->year) <= $this->emergingYoungJournal;
                }else{
                    return true;
                }
                
            })->pluck('parent_classification_id')->countBy();
        }

        $journalClassificationRates = [];
        foreach ($journalClassificationForEmerging as $classKey => $value) {
            if ($classKey == null) {
                continue;
            } else {
                if (!is_null($this->emergingCountryJournal) && $this->emergingCountryJournal != "") {
                    $years = collect(DB::table('journal_pivot_journal_category')->select('id', 'year', 'parent_classification_id', 'country_id')->where('parent_classification_id', $classKey)->get())->where('country_id', $this->emergingCountryJournal)->filter(function ($value, $key) {
                        if ($value->year != null  && $this->emergingYoungJournal != null && $this->emergingYoungJournal != ""){
                            return (date('Y') - (int)$value->year) <= $this->emergingYoungJournal;
                        }else{
                            return true;
                        }
                        
                    })->pluck('year')->countBy();
                } else {
                    $years = collect(DB::table('journal_pivot_journal_category')->select('id', 'year', 'parent_classification_id')->where('parent_classification_id', $classKey)->get())->filter(function ($value, $key) {
                        if ($value->year != null  && $this->emergingYoungJournal != null && $this->emergingYoungJournal != ""){
                            return (date('Y') - (int)$value->year) <= $this->emergingYoungJournal;
                        }else{
                            return true;
                        }
                        
                    })->pluck('year')->countBy();
                }

                // dd($years);
                $rate = 0;
                $addition = 0;
                $temp = null;
                foreach ($years as $key => $value) {
                    if ($temp != null) {
                        $rate = $rate + (((int)$value - (int)$temp) / (int)$value) * 100;
                        $addition = $addition + 1;
                    } else {
                        $temp = $value;
                    }
                }
                $journalClassification = JournalCategory::find($classKey);
                if ($journalClassification != null) {
                    array_push($journalClassificationRates, [
                        "key" => $journalClassification->category,
                        "value" => ($addition == 0) ? 0 : round($rate / $addition, 2)
                    ]);
                }
            }
        }
        rsort($journalClassificationRates);

        $this->emit("emergingJournalRate", [
            "test2" => $this->emergingCountryJournal,
            "test" => $journalClassificationForEmerging,
            "emergingJournalRate" => $journalClassificationRates
        ]);
    }

    public function updateTopPatentRate()
    {
        ini_set('memory_limit', '-1');

        if (!is_null($this->emergingCountryPatent) && $this->emergingCountryPatent != "") {
            $patentClassificationForEmerging = collect(DB::table('patent_pivot_patent_category')->select('id', 'year', 'parent_classification_id', 'country_id')->get())->where('country_id', $this->emergingCountryPatent)->filter(function ($value, $key) {
                if ($value->year != null  && $this->emergingYoungPatent != null && $this->emergingYoungPatent != ""){
                    return (date('Y') - (int)$value->year) <= $this->emergingYoungPatent;
                }else{
                    return true;
                }
                
            })->pluck('parent_classification_id')->countBy();
        } else {
            $patentClassificationForEmerging = collect(DB::table('patent_pivot_patent_category')->select('id', 'year', 'parent_classification_id')->get())->filter(function ($value, $key) {
                if ($value->year != null  && $this->emergingYoungPatent != null && $this->emergingYoungPatent != ""){
                    return (date('Y') - (int)$value->year) <= $this->emergingYoungPatent;
                }else{
                    return true;
                }
                
            })->pluck('parent_classification_id')->countBy();
        }

        $patentClassificationRates = [];
        foreach ($patentClassificationForEmerging as $classKey => $value) {
            if ($classKey == null) {
                continue;
            } else {
                if (!is_null($this->emergingCountryPatent) && $this->emergingCountryPatent != "") {
                    $years = collect(DB::table('patent_pivot_patent_category')->select('id', 'year', 'parent_classification_id', 'country_id')->where('parent_classification_id', $classKey)->get())->where('country_id', $this->emergingCountryPatent)->filter(function ($value, $key) {
                        if ($value->year != null  && $this->emergingYoungPatent != null && $this->emergingYoungPatent != ""){
                            return (date('Y') - (int)$value->year) <= $this->emergingYoungPatent;
                        }else{
                            return true;
                        }
                        
                    })->pluck('year')->countBy();
                } else {
                    $years = collect(DB::table('patent_pivot_patent_category')->select('id', 'year', 'parent_classification_id')->where('parent_classification_id', $classKey)->get())->filter(function ($value, $key) {
                        if ($value->year != null  && $this->emergingYoungPatent != null && $this->emergingYoungPatent != ""){
                            return (date('Y') - (int)$value->year) <= $this->emergingYoungPatent;
                        }else{
                            return true;
                        }
                        
                    })->pluck('year')->countBy();
                }
                $rate = 0;
                $addition = 0;
                $temp = null;
                foreach ($years as $key => $value) {
                    if ($temp != null) {
                        $rate = $rate + (((int)$value - (int)$temp) / (int)$value) * 100;
                        $addition = $addition + 1;
                    } else {
                        $temp = $value;
                    }
                }
                $patentClassification = PatentCategory::find($classKey);
                if ($patentClassification != null) {
                    array_push($patentClassificationRates, [
                        "key" => $patentClassification->classification_category,
                        "value" => ($addition == 0) ? 0 : round($rate / $addition, 2)
                    ]);
                }
            }
        }
        rsort($patentClassificationRates);

        $this->emit("emergingPatentRate", [
            "emergingPatentRate" => $patentClassificationRates
        ]);
    }

    public function updateTopPatent()
    {
        ini_set('memory_limit', '-1');

        $topPatentQuery =  DB::table('patent_pivot_patent_category')->select('id', 'parent_classification_id', 'country_id');

        if (!is_null($this->popularCountryPatent)) {
            $topPatentQuery = $topPatentQuery->where('country_id', $this->popularCountryPatent);
        }

        $topPatents = $topPatentQuery->get();

        $emergingPatentData = [];

        $emergingPatents = collect($topPatents)->pluck('parent_classification_id')->countBy()->sortByDesc(null)->take($this->topLimitPatent);

        foreach ($emergingPatents as $key => $value) {
            if ($key != null) {
                array_push($emergingPatentData, [
                    "key" => PatentCategory::find($key)->classification_category,
                    "value" => $value
                ]);
            } else {
                continue;
            }
        }

        $this->emit("updateTopPatent", [
            "emergingPatents" => $emergingPatentData
        ]);
    }

    public function updateTopJournal()
    {
        ini_set('memory_limit', '-1');
        $jounalQuery =  DB::table('journal_pivot_journal_category')->select('id', 'parent_classification_id', 'country_id');

        if (!is_null($this->popularCountryJournals)) {
            $jounalQuery = $jounalQuery->where('country_id', $this->popularCountryJournals);
        }

        $journals = $jounalQuery->get();

        $emergingJournalData = [];

        $emergingJournals = collect($journals)->pluck('parent_classification_id')->countBy()->sortByDesc(null)->take($this->topLimitJournal);

        foreach ($emergingJournals as $key => $value) {
            if ($key != null) {
                array_push($emergingJournalData, [
                    "key" => JournalCategory::find($key)->category,
                    "value" => $value
                ]);
            } else {
                continue;
            }
        }

        $this->emit("updateTopJournal", [
            "emergingJournals" => $emergingJournalData
        ]);
    }

    public function updateForecastChart()
    {
        $tempForcastData = $this->predict();

        $this->emit("reportsUpdated", [
            "forecastedFrom" =>  10,
            "forcastDates" => $tempForcastData["forecastedDates"],
            "forcastData" => $tempForcastData["forecastedData"],
            "forecastGraphLimit" => $tempForcastData["forecastGraphLimit"]
        ]);
    }

    public function updateForecastPatentChart()
    {
        $tempForcastPatentData = $this->predictPatent();

        $this->emit("reportsPatentUpdated", [
            "forecastedFrom" =>  10,
            "forcastPatentDates" => $tempForcastPatentData["forecastedPatentDates"],
            "forcastPatentData" => $tempForcastPatentData["forecastedPatentData"],
            "forecastPatentGraphLimit" => $tempForcastPatentData["forecastPatentGraphLimit"],
        ]);
    }

    public function updateForecastJournalChart()
    {
        $tempForcastJournalData = $this->predictJournal();

        $this->emit("reportsJournalUpdated", [
            "forecastedFrom" =>  10,
            "forcastJournalDates" => $tempForcastJournalData["forecastedJournalDates"],
            "forcastJournalData" => $tempForcastJournalData["forecastedJournalData"],
            "forecastJournalGraphLimit" => $tempForcastJournalData["forecastJournalGraphLimit"],
        ]);
    }

    public function updateTopChart()
    {

        ini_set('memory_limit', '-1');
        $businessQuery =  DB::table('businesses')->select('id', 'year', 'date_registered', 'industry_classification_id');
        $patentQuery =  DB::table('patents')->select('id', 'year', 'kind_id');
        $journalQuery =  DB::table('journals')->select('id', 'year');


        if ($this->topCountryFilter != null) {
            if ($this->classification != null) {
                $businessQuery = $businessQuery->where('country_id', $this->topCountryFilter)->where('industry_classification_id', $this->classification);
            } else {
                $businessQuery = $businessQuery->where('country_id', $this->topCountryFilter);
            }
            $patentQuery = $patentQuery->where('country_id', $this->topCountryFilter);
            $journalQuery = $journalQuery->where('country_id', $this->topCountryFilter);
        } else {
            if ($this->classification != null) {
                $businessQuery = $businessQuery->where('industry_classification_id', $this->classification);
            }
        }

        /* Get Query Data */
        $business = $businessQuery->get();
        $patents = $patentQuery->get();
        $journals = $journalQuery->get();

        /* Default data for Charts */

        $this->chartBusinessCount = collect($business)->pluck('year')->countBy(); // business chart count

        // $this->chartPatentsCount = collect($patents)->pluck('date')->countBy(function ($date) {
        //     $tempDate = substr(strchr($date, "/", 0), 4);
        //     if (strlen($tempDate) == 4) {
        //         return $tempDate;
        //     } else {
        //         return false;
        //     }
        // }); // Count of filtered patents with year extraction

        $this->chartPatentsCount =  collect($patents)->pluck('year')->countBy();

        $this->chartJournalsCount = collect($journals)->pluck('year')->countBy();
        // dd();

        /* Default data for Charts End*/
        $lineChartYears = array_unique($this->chartBusinessCount->keys()->concat($this->chartPatentsCount->keys()->concat($this->chartJournalsCount->keys()))->toArray());
        sort($lineChartYears);
        $lineChartYears = array_values(array_diff($lineChartYears, [0]));


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

        $this->emit("totalReportsUpdated", [
            "businessCount" => collect($business)->count(),
            "patentCount" => collect($patents)->count(),
            "journalCount" => collect($journals)->count(),
            "businessCountByYears" => collect($tempChartBusinessCount)->values(),
            "patentCountByYears" => collect($tempChartPatentsCount)->values(),
            "journalCountByYears" => collect($tempChartJournalsCount)->values(),
            "lineChartYears" => collect(($lineChartYears))->values(),
        ]);
    }

    public function filterData()
    {
        ini_set('memory_limit', '-1');
        $businessQuery =  DB::table('businesses')->select('id', 'year', 'date_registered', 'industry_classification_id');
        $patentQuery =  DB::table('patents')->select('id', 'year', 'kind_id');
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


        $businessClassificationForEmerging = collect(DB::table('businesses')->select('id', 'year', 'parent_classification_id')->where('year', '!=', date('Y'))->get())->filter(function ($value, $key) {
            if ($value->year != null  && $this->emergingYoungIndustry != null && $this->emergingYoungIndustry != ""){
                return (date('Y') - (int)$value->year) <= $this->emergingYoungIndustry;
            }else{
                return true;
            }
            
        })->pluck('parent_classification_id')->countBy();

        $businessClassificationRates = [];
        foreach ($businessClassificationForEmerging as $classKey => $value) {
            if ($classKey == null) {
                continue;
            } else {
                $years = collect(DB::table('businesses')->select('id', 'year', 'parent_classification_id')->where('parent_classification_id', $classKey)->where('year', '!=', date('Y'))->get())->filter(function ($value, $key) {
                    if ($value->year != null  && $this->emergingYoungIndustry != null && $this->emergingYoungIndustry != ""){
                        return (date('Y') - (int)$value->year) <= $this->emergingYoungIndustry;
                    }else{
                        return true;
                    }
                    
                })->pluck('year')->countBy();
                $arrayYears = $years->toArray();
                $listOfYears = array_keys($arrayYears);
                $listOfYears = array_map('intval', $listOfYears);
                $highestYear = (int) max($listOfYears);
                $lowYear = min($listOfYears);

                $rate = (($arrayYears[$highestYear] / $arrayYears[$lowYear]) ^ (1 / ($highestYear - $lowYear)) - 1) * 100;

                // // dd($years);
                // $rate = 0;
                // $addition = 0;
                // $temp = null;
                // foreach ($years as $key => $value) {
                //     if ($temp != null) {
                //         // $rate = $rate + (((int)$value - (int)$temp) / (int)$temp) * 100;
                //         $addition = $addition + 1;
                //         $temp = $value;
                //     } else {
                //         $temp = $value;
                //     }
                // }
                // $rate = $


                $industryClassification = IndustryClassification::find($classKey);
                if ($industryClassification != null) {
                    array_push($businessClassificationRates, [
                        "key" => $industryClassification->classifications,
                        "value" => round($rate, 2)
                    ]);
                }
            }
        }
        rsort($businessClassificationRates);


        $journalClassificationForEmerging = collect(DB::table('journal_pivot_journal_category')->select('id', 'year', 'parent_classification_id')->where('year', '!=', date('Y'))->get())->pluck('parent_classification_id')->countBy();

        $journalClassificationRates = [];
        foreach ($journalClassificationForEmerging as $classKey => $value) {
            if ($classKey == null) {
                continue;
            } else {
                $years = collect(DB::table('journal_pivot_journal_category')->select('id', 'year', 'parent_classification_id')->where('parent_classification_id', $classKey)->where('year', '!=', date('Y'))->get())->pluck('year')->countBy();
                // dd($years);
                $rate = 0;
                $addition = 0;
                $temp = null;
                foreach ($years as $key => $value) {
                    if ($temp != null) {
                        $rate = $rate + (((int)$value - (int)$temp) / (int)$temp) * 100;
                        $addition = $addition + 1;
                        $temp = $value;
                    } else {
                        $temp = $value;
                    }
                }
                $journalClassification = JournalCategory::find($classKey);
                if ($journalClassification != null) {
                    array_push($journalClassificationRates, [
                        "key" => $journalClassification->category,
                        "value" => ($addition == 0) ? 0 : round($rate / $addition, 2)
                    ]);
                }
            }
        }
        rsort($journalClassificationRates);


        $patentClassificationForEmerging = collect(DB::table('patent_pivot_patent_category')->select('id', 'year', 'parent_classification_id')->where('year', '!=', date('Y'))->get())->pluck('parent_classification_id')->countBy();

        $patentClassificationRates = [];
        foreach ($patentClassificationForEmerging as $classKey => $value) {
            if ($classKey == null) {
                continue;
            } else {
                $years = collect(DB::table('patent_pivot_patent_category')->select('id', 'year', 'parent_classification_id')->where('parent_classification_id', $classKey)->where('year', '!=', date('Y'))->get())->pluck('year')->countBy();
                // dd($years);
                $rate = 0;
                $addition = 0;
                $temp = null;
                foreach ($years as $key => $value) {
                    if ($temp != null) {
                        $rate = $rate + (((int)$value - (int)$temp) / (int)$temp) * 100;
                        $addition = $addition + 1;
                        $temp = $value;
                    } else {
                        $temp = $value;
                    }
                }
                $patentClassification = PatentCategory::find($classKey);
                if ($patentClassification != null) {
                    array_push($patentClassificationRates, [
                        "key" => $patentClassification->classification_category,
                        "value" => ($addition == 0) ? 0 : round($rate / $addition, 2)
                    ]);
                }
            }
        }
        rsort($patentClassificationRates);

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

        $topPatentQuery =  DB::table('patent_pivot_patent_category')->select('id', 'parent_classification_id', 'country_id');

        if (!is_null($this->popularCountryPatent)) {
            $topPatentQuery = $topPatentQuery->where('country_id', $this->popularCountryPatent);
        }

        $topPatents = $topPatentQuery->get();

        $emergingPatentData = [];

        $emergingPatents = collect($topPatents)->pluck('parent_classification_id')->countBy()->sortByDesc(null)->take(10);

        foreach ($emergingPatents as $key => $value) {
            if ($key != null) {
                array_push($emergingPatentData, [
                    "key" => PatentCategory::find($key)->classification_category,
                    "value" => $value
                ]);
            } else {
                continue;
            }
        }

        ini_set('memory_limit', '-1');
        $topJounalQuery =  DB::table('journal_pivot_journal_category')->select('id', 'parent_classification_id', 'country_id');

        if (!is_null($this->popularCountryJournals)) {
            $topJounalQuery = $topJounalQuery->where('country_id', $this->popularCountryJournals);
        }

        $topJournals = $topJounalQuery->get();

        $emergingJournalData = [];

        $emergingJournals = collect($topJournals)->pluck('parent_classification_id')->countBy()->sortByDesc(null)->take(10);

        foreach ($emergingJournals as $key => $value) {
            if ($key != null) {
                array_push($emergingJournalData, [
                    "key" => JournalCategory::find($key)->category,
                    "value" => $value
                ]);
            } else {
                continue;
            }
        }

        // $this->chartPatentsCount = collect($patents)->pluck('date')->countBy(function ($date) {
        //     $tempDate = substr(strchr($date, "/", 0), 4);
        //     if (strlen($tempDate) == 4) {
        //         return $tempDate;
        //     } else {
        //         return false;
        //     }
        // }); // Count of filtered patents with year extraction


        $this->chartPatentsCount =  collect($patents)->pluck('year')->countBy();

        $this->chartJournalsCount = collect($journals)->pluck('year')->countBy();
        // dd();

        /* Default data for Charts End*/
        $lineChartYears = array_unique($this->chartBusinessCount->keys()->concat($this->chartPatentsCount->keys()->concat($this->chartJournalsCount->keys()))->toArray());
        sort($lineChartYears);
        $lineChartYears = array_values(array_diff($lineChartYears, [0]));
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
                "emergingJournals" => $emergingJournalData,
                "emergingRate" => $businessClassificationRates,
                "emergingJournalRate" => $journalClassificationRates,
                "emergingPatentRate" => $patentClassificationRates
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

        if ($data["success"] == true) {
            return ["forecastedDates" => $data['prediction_data']['keys'], "forecastedData" => $data['prediction_data']['values'], "forecastGraphLimit" => max($data['prediction_data']['values']) + 1000];
        } else {
            return ["forecastedDates" => ['2011-01-01', '2011-02-01'], "forecastedData" => [], "forecastGraphLimit" => 1000];
        }
    }

    public function predictPatent()
    {
        $client = new Client();
        $res = $client->post('http://18.136.147.228/api/v1/predict', [
            'json' => [
                'country_id' => (!is_null($this->forecastPatentCountry) && $this->forecastPatentCountry != "") ? (int)$this->forecastPatentCountry : null,
                'classification_id' => (!is_null($this->forecastPatentClassification) && $this->forecastPatentClassification != "") ? (int)$this->forecastPatentClassification : null,
                'type' => "patent_pivot_patent_category"
            ]
        ]);
        $data = json_decode($res->getBody(), true);

        if ($data["success"] == true) {
            return ["forecastedPatentDates" => $data['prediction_data']['keys'], "forecastedPatentData" => $data['prediction_data']['values'], "forecastPatentGraphLimit" => max($data['prediction_data']['values']) + 1000];
        } else {
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

        if ($data["success"] == true) {
            return ["forecastedJournalDates" => $data['prediction_data']['keys'], "forecastedJournalData" => $data['prediction_data']['values'], "forecastJournalGraphLimit" => max($data['prediction_data']['values']) + 1000];
        } else {
            return ["forecastedJournalDates" => ['2011-01-01', '2011-02-01'], "forecastedJournalData" => [], "forecastJournalGraphLimit" => 1000];
        }
    }

    public function render()
    {
        // dd(DB::table('businesses')->select('id', 'year')->pluck('year')->countBy());
        $countries = Country::select('id', 'status', 'name')->where("status", "1")->get();
        $classifications = IndustryClassification::select('id', 'classifications')->where('parent_id', '!=', null)->where('classifications', '!=', null)->where('section_id', '!=', null)->where('division_id', '!=', null)->where('group_id', '!=', null)->get();
        $journalClassifications = JournalCategory::select('id', 'category')->where('division_id', null)->where('section_id', '!=', Null)->get();
        $patentClassifications = PatentCategory::select('id', 'classification_category')->where('class_id', null)->where('division_id', '!=', Null)->get();
        return view('livewire.client.report.report-component', [
            'countries' => $countries,
            'classifications' => $classifications,
            'journalClassifications' => $journalClassifications,
            'patentClassifications' => $patentClassifications,
            'allData' => DB::table('businesses')->select('id', 'year')->pluck('year')->countBy()
        ])->layout('layouts.client');
    }
}
