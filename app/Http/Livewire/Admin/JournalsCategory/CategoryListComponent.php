<?php

namespace App\Http\Livewire\Admin\JournalsCategory;

use App\Models\JournalCategory;
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
    public $selectedClass = Null;
    public $ajcs_code;
    public $category;

    public $btnType = 'Create';
    public $error;

    protected $listeners = ['refreshJournalCategoryListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'category'  => 'required'
        ];

        if ($this->is_parent) {
            return [
                'category'  => 'required',
                'acjs_code' => 'required',
            ];
        } else {
            return [
                'category'        => 'required',
                'selectedSection' => 'required|integer',
                'acjs_code'       => 'required',
            ];
        }
    }

    protected $messages = [
        'selectedSection.required' => 'Please select section category',
        'selectedSection.integer'  => 'You must select section category from drop down',
        'category.required'        => 'Please enter category title'
    ];

    public function mount()
    {
        $this->sections = JournalCategory::where('parent_id', Null)
            ->whereNot('id', $this->hiddenId)
            ->select('id', 'category')
            ->get();

        $this->divisions = collect();
        $this->groups = collect();
        $this->classes = collect();
    }

    public function render()
    {
        return view('livewire.admin.journals-category.category-list-component', [
            'journalsCategories' => JournalCategory::search($this->search)
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
                $journalCategory = JournalCategory::find($updateId); // update JournalCategory
            } else {
                $journalCategory = new JournalCategory(); // create JournalCategory
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

            $journalCategory->category        = $this->category;
            $journalCategory->parent_id       = $this->parent_id;
            if($this->is_parent == false) {
                $journalCategory->section_id  = $this->selectedSection;
                $journalCategory->division_id = $this->selectedDivision;
                $journalCategory->group_id    = $this->selectedGroup;
                $journalCategory->class_id    = $this->selectedClass;
            }else{
                $journalCategory->section_id  = Null;
                $journalCategory->division_id = Null;
                $journalCategory->group_id    = Null;
                $journalCategory->class_id    = Null;
            }
            $journalCategory->ajcs_code       = $this->ajcs_code;
            $journalCategory->save();

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
        $singleData             = JournalCategory::find($id);
        $this->hiddenId         = $singleData->id;
        $this->category         = $singleData->category;
        $this->selectedSection  = $singleData->section_id;
        $this->selectedDivision = $singleData->division_id;
        $this->selectedGroup    = $singleData->group_id;
        $this->selectedClass    = $singleData->class_id;
        $this->ajcs_code        = $singleData->ajcs_code;
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
            $data = JournalCategory::find($id);
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
            $data = JournalCategory::onlyTrashed()->find($id);
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
            'ajcs_code', 'is_parent', 'category', 'hiddenId', 'btnType', 'selectedSection', 
            'selectedDivision', 'selectedGroup', 'selectedClass', 'divisions', 'groups'
        );
    }

    // update division
    public function updatedSelectedSection($sectionID)
    {
        if (!is_null($sectionID)) {
            $this->divisions = JournalCategory::where('parent_id', $sectionID)
            ->whereNot('id', $this->hiddenId)
            ->get();
        }
    }

    // update Group
    public function updatedSelectedDivision($divisionID)
    {
        if (!is_null($divisionID)) {
            $this->groups = JournalCategory::where('parent_id', $divisionID)
            ->whereNot('id', $this->hiddenId)
            ->get();
        }
    }

    // update Class
    public function updatedSelectedGroup($groupID)
    {
        if (!is_null($groupID)) {
            $this->classes = JournalCategory::where('parent_id', $groupID)
            ->whereNot('id', $this->hiddenId)
            ->get();
        }
    }
}
