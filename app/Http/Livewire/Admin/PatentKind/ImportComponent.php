<?php

namespace App\Http\Livewire\Admin\PatentKind;

use App\Imports\PatentKindImport;
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
        return view('livewire.admin.patent-kind.import-component');
    }

    public function patentKindImport()
    {
        $this->validate([ 
            'file' => 'required'
        ]);

        DB::beginTransaction();

        try {

            Excel::import(new PatentKindImport, $this->file);

            DB::commit();
            
            $this->reset();
            $this->success = 'Intellectual Property Kind Imported Successfully';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
            $this->emit('refreshPatentKindListComponent');

        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            // $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    public function downloadSample()
    {
        try{
            return response()->download(storage_path("app\public\patent-kind-import-sample.csv"));
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
