<?php

namespace App\Http\Livewire\Client\Report;

use App\Models\Business;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReportComponent extends Component
{
    use WithPagination;

    public $type;
    public $reportData = [];
    public $records;
    protected $listeners = ['updateReport' => 'updateReportModel'];

    public function updateReportModel()
    {
        $this->getReport();
    }

    public function mount($type)
    {
        $this->type = $type;
        $this->getReport();
    }

    private function getReport()
    {
        $getReport = Business::groupBy('year')
                ->selectRaw('count(*) as total, year')
                ->groupBy('year')
                ->pluck('total','year');
        $this->reportData = $getReport;
        $this->getGraph();
    }

    private function getGraph()
    {
        dd($this->reportData);
        if(!empty($this->reportData)){
            foreach($this->reportData as $key => $value)
            {
                $data['label'][] = $key;
                $data['data'][] = $value;
            }
            $this->records = $data;
        }
    }

    public function render()
    {
        return view('livewire.client.report.report-component');
    }
}
