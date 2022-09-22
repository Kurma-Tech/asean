<?php

namespace App\Http\Livewire\Admin\Classification;

use App\Models\IndustryClassification;
use App\Models\Manpower;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AssignManpowerComponent extends Component
{
    public
        $manpower_id,
        $singleClassification,
        $seats,
        $manpowers = [],
        $inputs = [],
        $i = 1;

    public $updateMode = false;

    public $error;

    public function addFields($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs, $i);
    }

    public function removeFields($i)
    {
        unset($this->inputs[$i]);
    }

    public function mount()
    {
        $this->manpowers = Manpower::select('id', 'title', 'skilled')->get();
    }

    public function render()
    {
        return view('livewire.admin.classification.assign-manpower-component');
    }

    public function assignManpower()
    {
        // $this->validate(); // validate Patent form
        DB::beginTransaction();

        try {
            

            // $this->getClassification->sync();

            DB::commit();

            $this->dispatchBrowserEvent('success-message', ['message' => 'Manpower assign to classification']);

            $this->reset('classifications', 'parent_id', 'psic_code', 'hiddenId', 'btnType', 'is_parent');

        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = $th->getMessage();
            // $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }

    public function resetFields()
    {
        $this->reset('manpower_id', 'seets', 'inputs');
    }
}
