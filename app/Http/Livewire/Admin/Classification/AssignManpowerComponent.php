<?php

namespace App\Http\Livewire\Admin\Classification;

use App\Models\Manpower;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AssignManpowerComponent extends Component
{
    public
        $manpower_id,
        $classification_id,
        $seets,
        $manpowers = [],
        $inputs = [],
        $i = 1;

    public $updateMode = false;

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
        $this->validate(); // validate Patent form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if ($updateId > 0) {
                $industryClassification = IndustryClassification::find($updateId); // update IndustryClassification
            } else {
                $industryClassification = new IndustryClassification(); // create IndustryClassification
            }

            $industryClassification->classifications = $this->classifications;
            $industryClassification->parent_id       = $this->parent_id;
            $industryClassification->psic_code       = $this->psic_code;
            $industryClassification->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message', ['message' => 'Industry Classification has been ' . $this->btnType . '.']);

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
        $this->manpower_id = [];
        $this->seets       = [];
    }
}
