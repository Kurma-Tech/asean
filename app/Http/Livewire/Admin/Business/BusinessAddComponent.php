<?php

namespace App\Http\Livewire\Admin\Business;

use App\Models\Business;
use App\Models\BusinessType;
use App\Models\IndustryClassification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithFileUploads;

class BusinessAddComponent extends Component
{
    use WithFileUploads;

    public $fileHasHeader = true;
    public $csv_file;
    public $csv_data = [];
    public $db_fields = [];
    public $csv_header_cols = [];
    public $match_fields;
    public $data;
    public $failed = [];
    public $imported = false;

    public function render()
    {
        return view('livewire.admin.business.business-add-component')->layout('layouts.admin');
    }
}
