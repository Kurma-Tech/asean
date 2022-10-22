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

    public $parentCategories = []; // Categories Array

    public $hiddenId = 0;
    public $is_parent = 1;
    public $parent_id;
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
                'parent_id'               => 'required|integer',
                'ipc_code'                => 'required',
            ];
        }
    }

    protected $messages = [
        'parent_id.required'               => 'Please select parent classification category',
        'parent_id.integer'                => 'You must select parent classification category from drop down',
        'classification_category.required' => 'Please enter classification category title'
    ];

    public function render()
    {
        $this->parentCategories = PatentCategory::where('parent_id', null)->whereNot('id', $this->hiddenId)->select('id', 'classification_category')->get();
        return view('livewire.admin.patent-category.category-list-component', [
            'patentCategories' => PatentCategory::search($this->search)
                ->withTrashed()
                ->orderBy($this->orderBy, $this->sortBy ? 'asc' : 'desc')
                ->paginate($this->perPage),
            'parentCategories' => $this->parentCategories,
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

            $patentCategory->classification_category = $this->classification_category;
            $patentCategory->parent_id               = $this->parent_id;
            $patentCategory->ipc_code                = $this->ipc_code;
            $patentCategory->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message', ['message' => 'Category has been ' . $this->btnType . '.']);

            $this->reset('classification_category', 'parent_id', 'ipc_code', 'hiddenId', 'btnType', 'is_parent');
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
        $singleData                    = PatentCategory::find($id);
        $this->hiddenId                = $singleData->id;
        $this->classification_category = $singleData->classification_category;
        $this->parent_id               = $singleData->parent_id;
        $this->ipc_code                = $singleData->ipc_code;
        $this->is_parent               = $singleData->parent_id ? 0 : 1;
        $this->btnType                 = 'Update';
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
        $this->reset('classification_category', 'parent_id', 'ipc_code', 'hiddenId', 'btnType', 'is_parent');
    }
}
