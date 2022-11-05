<?php

namespace App\Http\Livewire\Admin\BusinessType;

use App\Models\BusinessType;
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
    public $type;
    public $slug;
    public $btnType = 'Create';

    public function generateslug()
    {
        $this->slug = SlugService::createSlug(BusinessType::class, 'slug', $this->type);
    }

    protected function rules()
    {
        return [
            'type' => 'required',
            'slug' => 'required|unique:patent_kinds,slug',
        ];
    }

    public function render()
    {
        return view('livewire.admin.business-type.business-list-component', [
            'businessTypes' => BusinessType::search($this->search)
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    // Store
    public function storeBusinessType()
    {
        $this->validate(); // validate BusinessType form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $bType = BusinessType::find($updateId); // Update BusinessType
            }
            else{
                $bType = new BusinessType(); // Create BusinessType
            }

            $bType->type  = $this->type;
            $bType->slug  = $this->slug;
            $bType->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message',['message' => 'Business Type has been ' . $this->btnType . '.']);

            $this->reset('type', 'slug', 'hiddenId', 'btnType');
            
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // Update Form
    public function editForm($type_id)
    {
        $singleType     = BusinessType::find($type_id);
        $this->hiddenId = $singleType->id;
        $this->type     = $singleType->type;
        $this->slug     = $singleType->slug;
        $this->btnType  = 'Update';
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = BusinessType::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'Business Type deleted successfully']);
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
            $data = BusinessType::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'Business Type restored successfully']);
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
        $this->reset('type', 'slug', 'hiddenId', 'btnType');
    }
}
