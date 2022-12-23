<?php

namespace App\Http\Livewire\Admin\Classification;

use App\Models\IndustryClassification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TrashedComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;
    
    public $error;

    public function render()
    {
        return view('livewire.admin.classification.trashed-component', [
            'industryClassificationsList' => IndustryClassification::search($this->search)
                ->onlyTrashed()
                ->orderBy($this->orderBy, $this->sortBy ? 'asc' : 'desc')
                ->paginate($this->perPage),
        ])->layout('layouts/admin');
    }

    // delete
    public function delete($id)
    {
        try {
            $data = IndustryClassification::where('id', $id);
            if ($data != null) {
                $data->forceDelete();
                $this->dispatchBrowserEvent('success-message', ['message' => 'Classification permanently deleted successfully']);
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
}