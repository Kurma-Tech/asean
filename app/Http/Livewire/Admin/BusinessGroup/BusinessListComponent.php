<?php

namespace App\Http\Livewire\Admin\BusinessGroup;

use App\Models\BusinessGroup;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class BusinessListComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public $error;

    public $hiddenId = 0;
    public $group;
    public $slug;
    public $btnType = 'Create';

    public function generateslug()
    {
        $this->slug = SlugService::createSlug(BusinessGroup::class, 'slug', $this->type);
    }

    protected function rules()
    {
        return [
            'group' => 'required',
            'slug' => 'required|unique:patent_kinds,slug',
        ];
    }

    public function render()
    {
        return view('livewire.admin.business-group.business-list-component', [
            'businessGroups' => BusinessGroup::search($this->search)
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    // Store
    public function storeBusinessGroup()
    {
        $this->validate(); // validate BusinessGroup form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $bType = BusinessGroup::find($updateId); // Update BusinessGroup
            }
            else{
                $bType = new BusinessGroup(); // Create BusinessGroup
            }

            $bType->group  = $this->group;
            $bType->slug  = $this->slug;
            $bType->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message',['message' => 'Business group has been ' . $this->btnType . '.']);

            $this->resetFields();
            
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // Update Form
    public function editForm($group_id)
    {
        $singleGroup    = BusinessGroup::find($group_id);
        $this->hiddenId = $singleGroup->id;
        $this->group    = $singleGroup->group;
        $this->slug     = $singleGroup->slug;
        $this->btnType  = 'Update';
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = BusinessGroup::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'Business group deleted successfully']);
            }else{
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            // $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // restore
    public function restore($id)
    {
        try {
            $data = BusinessGroup::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'Business group restored successfully']);
            }else{
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // reset fields
    public function resetFields()
    {
        $this->reset('group', 'slug', 'hiddenId', 'btnType');
    }
}
