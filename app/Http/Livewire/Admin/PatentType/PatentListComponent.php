<?php

namespace App\Http\Livewire\Admin\PatentType;

use App\Models\PatentType;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PatentListComponent extends Component
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

    protected $listeners = ['refreshPatentTypeListComponent' => '$refresh'];

    public function generateslug()
    {
        $this->slug = SlugService::createSlug(PatentType::class, 'slug', $this->type);
    }

    protected function rules()
    {
        return [
            'type' => 'required',
            'slug' => 'required|unique:patent_types,slug',
        ];
    }

    public function render()
    {
        return view('livewire.admin.patent-type.patent-list-component', [
            'patentTypes' => PatentType::search($this->search)
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    // Store
    public function storePatentType()
    {
        $this->validate(); // validate PatentType form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $pType = PatentType::find($updateId); // update PatentType
            }
            else{
                $pType = new PatentType(); // create PatentType
            }
            
            $pType->type  = $this->type;
            $pType->slug  = $this->slug;
            $pType->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message',['message' => 'Intellectual Property Type has been ' . $this->btnType . '.']);

            $this->reset('type', 'slug', 'hiddenId', 'btnType');
            
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = $th->getMessage();
            // $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // Update Form
    public function editForm($type_id)
    {
        $singleType     = PatentType::find($type_id);
        $this->hiddenId = $singleType->id;
        $this->type     = $singleType->type;
        $this->slug     = $singleType->slug;
        $this->btnType  = 'Update';
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = PatentType::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'Intellectual Property Type Deleted Successfully']);
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
            $data = PatentType::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'Intellectual Property Type Restored Successfully']);
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
