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

    // public $parentCategories = []; // Categories Array

    public $hiddenId = 0;
    public $category;

    public $btnType = 'Create';
    public $error;

    protected $listeners = ['refreshJournalCategoryListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'category'  => 'required'
        ];
    }

    protected $messages = [
        'parent_id.required' => 'Please select parent category',
        'parent_id.integer'  => 'You must select parent category from drop down',
        'category.required'  => 'Please enter category title',
    ];

    public function mount()
    {
        // $this->parentCategories = JournalCategory::where('parent_id', null)->select('id', 'category')->get();
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

            $journalCategory->category  = $this->category;
            // $journalCategory->parent_id = $this->parent_id;
            $journalCategory->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message', ['message' => 'Category has been ' . $this->btnType . '.']);

            $this->reset('category', 'hiddenId', 'btnType');
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
        $singleData      = JournalCategory::find($id);
        $this->hiddenId  = $singleData->id;
        $this->category  = $singleData->category;
        $this->btnType   = 'Update';
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
        $this->reset('category', 'hiddenId', 'btnType');
    }
}
