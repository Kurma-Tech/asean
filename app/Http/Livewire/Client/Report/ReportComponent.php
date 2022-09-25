<?php

namespace App\Http\Livewire\Client\Report;

use Livewire\Component;
use Livewire\WithPagination;

class ReportComponent extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.client.report.report-component')->layout('layouts.client');
    }
}
