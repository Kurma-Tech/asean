<?php

namespace App\Http\Livewire\Client\Report;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Country;
use App\Models\IndustryClassification;

class ReportComponent extends Component
{
    public
        $chartBusinessCount,
        $chartPatentsCount,
        $chartJournalsCount,
        $country,
        $classification,
        $isFirstLoad = true;

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

    public function updatedClassification($classification)
    {
        $this->countclassificationry = $classification;
        $this->filterData();
    }

    public function filterData()
    {
        ini_set('memory_limit', '-1');
        $businessQuery =  DB::table('businesses')->select('id', 'year', 'date_registered', 'industry_classification_id');
        $patentQuery =  DB::table('patents')->select('id', 'registration_date');
        $journalQuery =  DB::table('journals')->select('id', 'published_year');


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

        /* Default data for Charts */

        $this->chartBusinessCount = collect($business)->pluck('year')->countBy(); // business chart count

        $emergingBusinessData = [];

        $emergingBusiness = collect($business)->pluck('industry_classification_id')->countBy()->sortByDesc(null)->take(10);

        foreach ($emergingBusiness as $key => $value) {
            array_push($emergingBusinessData, [
                "key" => IndustryClassification::find($key)->classifications,
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

        $this->chartJournalsCount = collect($journals)->pluck('published_year')->countBy();

        /* Default data for Charts End*/
        $lineChartYears = array_unique($this->chartBusinessCount->keys()->concat($this->chartPatentsCount->keys())->toArray());
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
        $tempChartBusinessCountForForecast = [];
        for ($i = 0; $i < count($lineChartYears); $i++) {
            try {
                if ($this->chartBusinessCount->has($lineChartYears[$i])) {
                    $tempChartBusinessCount[$lineChartYears[$i]] = $this->chartBusinessCount[$lineChartYears[$i]];
                    $tempChartBusinessCountForForecast[$lineChartYears[$i]] = $this->chartBusinessCount[$lineChartYears[$i]];
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

        $this->tempForcastData = $this->predict(collect($tempChartBusinessCountForForecast)->keys(), collect($tempChartBusinessCountForForecast)->values());

        if ($this->isFirstLoad) {
            $this->emit("reportsFirstLoad", [
                "businessCountByYears" => collect($tempChartBusinessCount)->values(),
                "patentCountByYears" => collect($tempChartPatentsCount)->values(),
                "journalCountByYears" => collect($tempChartJournalsCount)->values(),
                "lineChartYears" => collect(($lineChartYears))->values(),
                "forecastedFrom" =>  $this->tempForcastData["forecastedDates"]->count() - collect($tempChartBusinessCount)->keys()->count(),
                "forcastDates" => $this->tempForcastData["forecastedDates"],
                "forcastData" => $this->tempForcastData["forecastedData"],
                "emergingBusiness" => $emergingBusinessData
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
                "forcastData" => $this->tempForcastData["forecastedData"]
            ]);
        }
    }

    public function predict($forcastDates, $forcastData)
    {
        $nF = 0;
        $nL = $forcastDates->last() - $forcastDates->first();
        $pF = $forcastData->first();
        $pL = $forcastData->last();
        $last = $forcastDates->last();
        $forecastedDates = $forcastDates;
        $forecastedData = $forcastData;

        if ($forcastDates->count() != 0 && $nL != 0 && $pL != 0) {
            $r = pow($pL / $pF, 1 / $nL) - 1;

            for ($i = 0; $i < 10; $i++) {
                $forecastedDates->push($last + $i + 1);
                $forecastedData->push(intval((pow(1 + $r, $nL + $i + 1)) * $pF));
            }
        }
        // $p5 = (pow(1 + $r, 5)) * $pF;
        // dd($forecastedData);
        return ["forecastedDates" => $forecastedDates, "forecastedData" => $forecastedData];
    }

    public function render()
    {
        // dd(DB::table('businesses')->select('id', 'year')->pluck('year')->countBy());
        $countries = Country::select('id', 'status', 'name')->where("status", "1")->get();
        $classifications = IndustryClassification::select('id', 'classifications')->where('parent_id', '!=', null)->where('classifications', '!=', null)->get();
        return view('livewire.client.report.report-component', [
            'countries' => $countries,
            'classifications' => $classifications,
            'allData' => DB::table('businesses')->select('id', 'year')->pluck('year')->countBy()
        ])->layout('layouts.client');
    }
}
