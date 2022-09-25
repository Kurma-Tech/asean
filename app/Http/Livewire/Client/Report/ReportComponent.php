<?php

namespace App\Http\Livewire\Client\Report;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReportComponent extends Component
{
    public
        $chartBusinessCount,
        $chartPatentsCount;

    protected
        $business = [],
        $patents = [],
        $allData = [];

    protected $listeners = [
        'reportFirstLoad' => 'reportHandleFirstLoad',
    ];

    public function reportHandleFirstLoad()
    {
        $this->filterData();
    }

    public function filterData()
    {
        $businessQuery =  DB::table('businesses')->select('id', 'year');
        $patentQuery =  DB::table('patents')->select('id', 'date');

        /* Get Query Data */
        $this->business = $businessQuery->get();
        $this->patents = $patentQuery->get();
        /* Get Query Data End */

        /* Default data for Charts */

        $this->chartBusinessCount = collect($this->business)->pluck('year')->countBy(); // business chart count
        
        $this->chartPatentsCount = collect($this->patents)->pluck('date')->countBy(function ($date) {
            return substr(strchr($date, "/", 0), 4); 
        }); // Count of filtered patents with year extraction

        // dd($this->chartPatentsCount);

        /* Default data for Charts End*/
        $lineChartYears = $this->chartBusinessCount->keys()->concat($this->chartPatentsCount->keys())->unique();
        // dd($lineChartYears);
        $tempChartPatentsCount = [];
        for ($i=0; $i < count($lineChartYears); $i++) { 
            if($this->chartPatentsCount->has($lineChartYears[$i])){
                $tempChartPatentsCount[$lineChartYears[$i]] = $this->chartPatentsCount[$lineChartYears[$i]];
            }else{
                $tempChartPatentsCount[$lineChartYears[$i]] = null;
            }
        }

        $tempChartBusinessCount = [];
        for ($i=0; $i < count($lineChartYears); $i++) { 
            if($this->chartBusinessCount->has($lineChartYears[$i])){
                $tempChartBusinessCount[$lineChartYears[$i]] = $this->chartBusinessCount[$lineChartYears[$i]];
            }else{
                $tempChartBusinessCount[$lineChartYears[$i]] = null;
            }
        }

        // dd($lineChartYears->sort());

        $this->emit("reportsUpdated", ["businessCountByYears" => collect($tempChartBusinessCount)->values(), "patentCountByYears" => collect($tempChartPatentsCount)->values(), "lineChartYears" => $lineChartYears->sort()]);
    }

    public function render()
    {
        return view('livewire.client.report.report-component')->layout('layouts.client');
    }
}
