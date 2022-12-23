<?php

namespace App\Http\Livewire\Admin\Patent;

use App\Imports\PatentImport;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportComponent extends Component
{
    use WithFileUploads;

    public $file;

    public $error;
    public $success;

    public function updated($fields)
    {
        $this->validateOnly($fields, [
            'file' => 'required'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.patent.import-component');
    }

    public function patentImport()
    {
        $this->validate([ 
            'file' => 'required'
        ]);

        DB::beginTransaction();

        try {

            Excel::import(new PatentImport, $this->file);

            DB::commit();
            
            $this->reset();
            $this->success = 'Intellectual Property Imported Successfully';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
            $this->emit('refreshPatentListComponent');

        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    public function downloadSample()
    {
        try{
            return response()->download(storage_path("app\public\patent-import-sample.csv"));
            $this->success = 'Patent Kind Sample Downloaded';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            // $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }
}
