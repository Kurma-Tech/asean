<?php

namespace App\Http\Livewire\Admin\Manpower;

use App\Models\Manpower;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ManpowerListComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public $error;

    public $hiddenId = 0;
    public $title;
    public $description;
    public $skilled;
    public $status = 1;
    public $btnType = 'Create';

    protected $listeners = ['refreshManpowerListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'title' => 'required',
            'description' => 'nullable|min:10',
            'skilled' => 'required|in:PROFESSIONAL,TRADESMAN',
            'status' => 'boolean'
        ];
    }

    public function render()
    {
        return view('livewire.admin.manpower.manpower-list-component', [
            'manpowers' => Manpower::search($this->search)
                ->withTrashed()
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    // Store
    public function storeManpower()
    {
        $this->validate(); // validate Manpower form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;

            if($updateId > 0)
            {
                $manpower = Manpower::find($updateId); // update Manpower
            }
            else{
                $manpower = new Manpower(); // create Manpower
            }

            $manpower->title       = $this->title;
            $manpower->description = $this->description;
            $manpower->skilled     = $this->skilled;
            $manpower->status      = $this->status;
            $manpower->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message',['message' => 'Manpower has been ' . $this->btnType . '.']);

            $this->reset('title', 'description', 'skilled', 'status', 'hiddenId', 'btnType');
            
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = $th->getMessage();
            // $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // Update Form
    public function editForm($manpower_id)
    {
        $singleManpower    = Manpower::find($manpower_id);
        $this->hiddenId    = $singleManpower->id;
        $this->title       = $singleManpower->title;
        $this->skilled     = $singleManpower->skilled;
        $this->description = $singleManpower->description;
        $this->status      = $singleManpower->status;
        $this->btnType     = 'Update';
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = Manpower::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'Manpower deleted successfully']);
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
            $data = Manpower::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'Manpower restored successfully']);
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
        $this->reset('title', 'description', 'skilled', 'status', 'hiddenId', 'btnType');
    }
}
