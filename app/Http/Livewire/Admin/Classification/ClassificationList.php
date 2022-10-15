<?php

namespace App\Http\Livewire\Admin\Classification;

use App\Models\IndustryClassification;
use App\Models\Manpower;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ClassificationList extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public // Manpower Asign Array
           $inputs = [], $manpowers = [], $classificationManpowers = [],
           // Classification Array
           $parentClassifications = [];

    public $hiddenId = 0;
    public $is_parent = 1;
    public $parent_id;
    public $psic_code;
    public $classifications;
    // manpower assign
    public $manpower_id;
    public $seats;
    public $i = 1;

    public $btnType = 'Create';
    public $error;

    protected $listeners = ['refreshClassificationListComponent' => '$refresh'];

    protected function rules()
    {
        if ($this->is_parent) {
            return [
                'classifications' => 'required'
            ];
        } else {
            return [
                'classifications' => 'required',
                'parent_id'       => 'required|integer',
                'psic_code'       => 'required',
                'manpower_id.0'   => 'required',
                'seats.0'         => 'required',
                'manpower_id.*'   => 'required',
                'seats.*'         => 'required|integer'
            ];
        }
    }

    protected $messages = [
        'parent_id.required'       => 'Please select parent classification',
        'parent_id.integer'        => 'You must select parent classification from drop down',
        'classifications.required' => 'Please enter classification title',
        'manpower_id.0.required'   => 'Manpower field is required',
        'seats.0.required'         => 'Seats field is required',
        'seats.0.integer'          => 'The seats must be a integer.',
        'manpower_id.*.required'   => 'Manpower field is required',
        'seats.*.required'         => 'Seats field is required',
        'seats.*.integer'          => 'The seats must be a integer.'
    ];

    public function mount()
    {
        $this->manpowers = Manpower::select('id', 'title', 'skilled')->get();
        $this->parentClassifications = IndustryClassification::where('parent_id', null)->select('id', 'classifications')->get();
    }

    public function render()
    {
        return view('livewire.admin.classification.classification-list', [
            'industryClassificationsList' => IndustryClassification::search($this->search)
                ->withTrashed()
                ->orderBy($this->orderBy, $this->sortBy ? 'asc' : 'desc')
                ->paginate($this->perPage),
        ])->layout('layouts/admin');
    }

    // Store
    public function storeClassification()
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

            $manpowers = [];
            foreach($this->manpower_id as $key => $value)
            {
                $manpowers[$value] = ['seats' => $this->seats[$key]];
            }

            $industryClassification->manpowers()->sync($manpowers);
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

    // Update Form
    public function editForm($id)
    {
        $singleData                    = IndustryClassification::find($id);
        $this->hiddenId                = $singleData->id;
        $this->classifications         = $singleData->classifications;
        $this->parent_id               = $singleData->parent_id;
        $this->psic_code               = $singleData->psic_code;
        $this->is_parent               = $singleData->parent_id ? 0 : 1;
        $this->btnType                 = 'Update';
        $this->classificationManpowers = $singleData->manpowers;
        
        // foreach($this->classificationManpowers as $key => $value)
        // {
        //     $this->manpower_id.'.'.$key = $value->id;
        //     $this->seats.'.'.$key = $value->pivot->seats;
        // }
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = IndustryClassification::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message', ['message' => 'Classification deleted successfully']);
            } else {
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            // $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }

    // restore
    public function restore($id)
    {
        try {
            $data = IndustryClassification::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message', ['message' => 'Classification restored successfully']);
            } else {
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }

    // reset fields
    public function resetFields()
    {
        $this->reset('classifications', 'parent_id', 'psic_code', 'hiddenId', 'btnType', 'is_parent');
        $this->reset('manpower_id', 'seets', 'inputs');
    }

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
}
