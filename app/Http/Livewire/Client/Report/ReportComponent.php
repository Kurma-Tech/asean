<?php

namespace App\Http\Livewire\Client\Report;

use App\Models\Business;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReportComponent extends Component
{
    use WithPagination;

    public $type, $country, $classification;

    public function render()
    {
        return view('livewire.client.report.report-component');
    }
}
