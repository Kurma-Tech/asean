<?php

namespace App\Http\Livewire\Admin\PatentType;

use App\Imports\PatentTypeImport;
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

    protected function rules()
    {
        return [
            'file' => 'required|mimes:xlsx,xls,csv,txt|max:102400'
        ];
    }

    public function render()
    {
        return view('livewire.admin.patent-type.import-component');
    }

    public function patentTypeImport()
    {
        $this->validate();

        DB::beginTransaction();

        try {

            Excel::import(new PatentTypeImport, $this->file);

            DB::commit();
            
            $this->reset();
            $this->success = 'Intellectual Property Type Imported Successfully';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
            $this->emit('refreshPatentTypeListComponent');

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
            return response()->download(storage_path("app\public\patent-type-import-sample.csv"));
            $this->success = 'Patent Type Sample Downloaded';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            // $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }
}
