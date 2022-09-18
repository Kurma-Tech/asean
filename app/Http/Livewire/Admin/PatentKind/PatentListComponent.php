<?php

namespace App\Http\Livewire\Admin\PatentKind;

use App\Models\PatentKind;
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
    public $kind;
    public $slug;
    public $btnType = 'Create';

    protected $listeners = ['refreshPatentKindListComponent' => '$refresh'];

    public function generateslug()
    {
        $this->slug = SlugService::createSlug(PatentKind::class, 'slug', $this->kind);
    }

    protected function rules()
    {
        return [
            'kind' => 'required|min:3',
            'slug' => 'required|min:3|unique:patent_kinds,slug',
        ];
    }

    public function render()
    {
        return view('livewire.admin.patent-kind.patent-list-component', [
            'patentKinds' => PatentKind::search($this->search)
                ->withTrashed()
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    // Store
    public function storePatentKind()
    {
        $this->validate(); // validate PatentKind form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $pKind = PatentKind::find($updateId); // update PatentKind
            }
            else{
                $pKind = new PatentKind(); // create PatentKind
            }

            $pKind->kind  = $this->kind;
            $pKind->slug  = $this->slug;
            $pKind->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message',['message' => 'Patent Kind has been ' . $this->btnType . '.']);

            $this->reset('kind', 'slug', 'hiddenId', 'btnType');
            
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // Update Form
    public function editForm($kind_id)
    {
        $singleKind     = PatentKind::find($kind_id);
        $this->hiddenId = $singleKind->id;
        $this->kind     = $singleKind->kind;
        $this->slug     = $singleKind->slug;
        $this->btnType  = 'Update';
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = PatentKind::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'Patent Kind deleted successfully']);
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
            $data = PatentKind::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'Patent Kind restored successfully']);
            }else{
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = $th->getMessage();
            // $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // reset fields
    public function resetFields()
    {
        $this->reset('kind', 'slug', 'hiddenId', 'btnType');
    }
}
