<?php

namespace App\Http\Livewire\Admin\PatentCategory;

use App\Models\PatentCategory;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryListComponent extends Component
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
    public $selectedSection = Null;
    public $selectedDivision = Null;
    public $selectedGroup = Null;
    public $ipc_code;
    public $classification_category;

    public $btnType = 'Create';
    public $error;

    protected $listeners = ['refreshClassificationListComponent' => '$refresh'];

    protected function rules()
    {
        if ($this->is_parent) {
            return [
                'classification_category' => 'required',
                'ipc_code'                => 'required',
            ];
        } else {
            return [
                'classification_category' => 'required',
                'selectedSection'         => 'required|integer',
                'ipc_code'                => 'required',
            ];
        }
    }

    protected $messages = [
        'selectedSection.required'         => 'Please select section category',
        'selectedSection.integer'          => 'You must select section category from drop down',
        'classification_category.required' => 'Please enter classification category title'
    ];

    public function mount()
    {
        $this->sections = PatentCategory::where('parent_id', Null)
            ->whereNot('id', $this->hiddenId)
            ->select('id', 'classification_category')
            ->get();

        $this->divisions = collect();
        $this->groups = collect();
    }

    public function render()
    {
        return view('livewire.admin.patent-category.category-list-component', [
            'patentCategories' => PatentCategory::search($this->search)
            ->orderBy($this->orderBy, $this->sortBy ? 'asc' : 'desc')
            ->paginate($this->perPage),
        ])->layout('layouts/admin');
    }

    // Store
    public function storeCategory()
    {
        $this->validate(); // validate Patent form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if ($updateId > 0) {
                $patentCategory = PatentCategory::find($updateId); // update PatentCategory
            } else {
                $patentCategory = new PatentCategory(); // create PatentCategory
            }

            if($this->is_parent == 0)
            {
                $this->selectedSection = Null;
                $this->selectedDivision = Null;
                $this->selectedGroup = Null;
            }

            if(is_null($this->selectedSection))
            {
                $this->parent_id = Null;
            }elseif(!is_null($this->selectedSection) && is_null($this->selectedDivision))
            {
                $this->parent_id = $this->selectedSection;
            }elseif(!is_null($this->selectedSection) && !is_null($this->selectedDivision) && is_null($this->selectedGroup))
            {
                $this->parent_id = $this->selectedDivision;
            }elseif(!is_null($this->selectedSection) && !is_null($this->selectedDivision) && !is_null($this->selectedGroup) && is_null($this->selectedClass))
            {
                $this->parent_id = $this->selectedGroup;
            }

            $patentCategory->classification_category = $this->classification_category;
            $patentCategory->parent_id               = $this->parent_id ?? Null;
            $patentCategory->section_id              = $this->selectedSection ?? Null;
            $patentCategory->division_id             = $this->selectedDivision ?? Null;
            $patentCategory->group_id                = $this->selectedGroup ?? Null;
            $patentCategory->ipc_code                = $this->ipc_code;
            $patentCategory->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message', ['message' => 'Category has been ' . $this->btnType . '.']);

            $this->resetFields();

        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }

    // Update Form
    public function editForm($id)
    {
        // $this->resetFields();

        $singleData                    = PatentCategory::find($id);
        $this->hiddenId                = $singleData->id;
        $this->classification_category = $singleData->classification_category;
        $this->selectedSection         = $singleData->section_id;
        $this->selectedDivision        = $singleData->division_id;
        $this->selectedGroup           = $singleData->group_id;
        $this->selectedClass           = $singleData->class_id;
        $this->ipc_code                = $singleData->ipc_code;
        $this->is_parent               = $singleData->parent_id ? 0 : 1;
        $this->btnType                 = 'Update';

        $this->updatedSelectedSection($this->selectedSection);
        $this->updatedSelectedDivision($this->selectedDivision);
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = PatentCategory::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message', ['message' => 'Category deleted successfully']);
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
            $data = PatentCategory::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message', ['message' => 'Category restored successfully']);
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
            'classification_category', 'ipc_code', 'is_parent', 
            'selectedSection', 'selectedDivision', 'selectedGroup', 
            'hiddenId', 'btnType', 'divisions', 'groups'
        );
    }

    // update division
    public function updatedSelectedSection($sectionID)
    {
        if (!is_null($sectionID)) {
            $this->divisions = PatentCategory::where('parent_id', $sectionID)
            ->whereNot('id', $this->hiddenId)
            ->get();
        }
    }

    // update Group
    public function updatedSelectedDivision($divisionID)
    {
        if (!is_null($divisionID)) {
            $this->groups = PatentCategory::where('parent_id', $divisionID)
            ->whereNot('id', $this->hiddenId)
            ->get();
        }
    }
}
