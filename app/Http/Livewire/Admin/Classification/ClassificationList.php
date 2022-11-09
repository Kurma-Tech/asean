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

    public $sections; // Categories Array
    public $divisions; // Categories Array
    public $groups; // Categories Array
    public $classes; // Categories Array

    public $hiddenId = 0;
    public $is_parent = 1;
    public $parent_id;
    public $code;
    public $classifications;
    public $selectedSection = Null;
    public $selectedDivision = Null;
    public $selectedGroup = Null;
    public $selectedClass = Null;

    public $btnType = 'Create';
    public $error;

    protected $listeners = ['refreshClassificationListComponent' => '$refresh'];

    protected function rules()
    {
        if ($this->is_parent) {
            return [
                'classifications' => 'required',
                'code'            => 'required'
            ];
        } else {
            return [
                'classifications' => 'required',
                'selectedSection' => 'required|integer',
                'code'            => 'required'
            ];
        }
    }

    protected $messages = [
        'classifications.required' => 'Please enter classification title',
        'selectedSection.required' => 'Please select section category',
        'selectedSection.integer'  => 'You must select section category from drop down',
    ];

    public function mount()
    {
        $this->sections = IndustryClassification::where('parent_id', Null)
            ->whereNot('id', $this->hiddenId)
            ->select('id', 'classifications')
            ->get();

        $this->divisions = collect();
        $this->groups = collect();
        $this->classes = collect();
    }

    public function render()
    {
        return view('livewire.admin.classification.classification-list', [
            'industryClassificationsList' => IndustryClassification::search($this->search)
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

            if(is_null($this->selectedSection))
            {
                $this->parent_id = Null;
            }
            elseif(!is_null($this->selectedSection) && is_null($this->selectedDivision))
            {
                $this->parent_id = $this->selectedSection;
            }
            elseif(!is_null($this->selectedSection) && !is_null($this->selectedDivision) && is_null($this->selectedGroup))
            {
                $this->parent_id = $this->selectedDivision;
            }
            elseif(!is_null($this->selectedSection) && !is_null($this->selectedDivision) && !is_null($this->selectedGroup) && is_null($this->selectedClass))
            {
                $this->parent_id = $this->selectedGroup;
            }
            elseif(!is_null($this->selectedSection) && !is_null($this->selectedDivision) && !is_null($this->selectedGroup) && !is_null($this->selectedClass))
            {
                $this->parent_id = $this->selectedClass;
            }

            $industryClassification->classifications = $this->classifications;
            $industryClassification->parent_id       = $this->parent_id ?? Null;
            if($this->is_parent == false) {
                $industryClassification->section_id  = $this->selectedSection;
                $industryClassification->division_id = $this->selectedDivision;
                $industryClassification->group_id    = $this->selectedGroup;
                $industryClassification->class_id    = $this->selectedClass;
            }else{
                $industryClassification->section_id  = Null;
                $industryClassification->division_id = Null;
                $industryClassification->group_id    = Null;
                $industryClassification->class_id    = Null;
            }
            $industryClassification->code            = $this->code;
            $industryClassification->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message', ['message' => 'Industry Classification has been ' . $this->btnType . '.']);

            $this->resetFields();
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
        $singleData             = IndustryClassification::find($id);
        $this->hiddenId         = $singleData->id;
        $this->classifications  = $singleData->classifications;
        $this->selectedSection  = $singleData->section_id;
        $this->selectedDivision = $singleData->division_id;
        $this->selectedGroup    = $singleData->group_id;
        $this->selectedClass    = $singleData->class_id;
        $this->code             = $singleData->code;
        $this->is_parent        = $singleData->parent_id ? 0 : 1;
        $this->btnType          = 'Update';

        $this->updatedSelectedSection($this->selectedSection);
        $this->updatedSelectedDivision($this->selectedDivision);
        $this->updatedSelectedGroup($this->selectedGroup);
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
        $this->reset(
            'classifications', 'parent_id', 'code', 
            'selectedSection', 'selectedDivision', 'selectedGroup', 'selectedClass', 
            'hiddenId', 'btnType', 'divisions', 'groups'
        );
    }

    // update division
    public function updatedSelectedSection($sectionID)
    {
        if (!is_null($sectionID)) {
            $this->divisions = IndustryClassification::where('parent_id', $sectionID)
            ->select('id', 'classifications')
            ->whereNot('id', $this->hiddenId)
            ->get();
        }
    }

    // update Group
    public function updatedSelectedDivision($divisionID)
    {
        if (!is_null($divisionID)) {
            $this->groups = IndustryClassification::where('parent_id', $divisionID)
            ->select('id', 'classifications')
            ->whereNot('id', $this->hiddenId)
            ->get();
        }
    }

    // update Class
    public function updatedSelectedGroup($groupID)
    {
        if (!is_null($groupID)) {
            $this->classes = IndustryClassification::where('parent_id', $groupID)
            ->select('id', 'classifications')
            ->whereNot('id', $this->hiddenId)
            ->get();
        }
    }
}
