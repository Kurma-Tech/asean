<?php

namespace App\Http\Livewire\Admin\Business;

// use App\Imports\BusinessImport;

use App\Imports\BusinessImport;
use App\Jobs\ProcessImport;
use App\Models\JobBatch;
use Illuminate\Support\Facades\Bus;
// use App\Jobs\ProcessImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportComponent extends Component
{
    use WithFileUploads;

    public $file;

    public $importing = false;
    public $filePath;
    public $importFinished = false;
    public $failed = false;

    public $error;
    public $success;

    protected function rules()
    {
        return [
            'file' => 'required|mimes:xlsl,xls,csv'
        ];
    }

    public function businessImport()
    {
        $this->validate();

        DB::beginTransaction();

        try {

            Excel::import(new BusinessImport, $this->filePath);

            DB::commit();
            
            $this->reset();
            $this->success = 'Business Data Imported Successfully';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);

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
            return response()->download(storage_path("app\public\business-import-sample.csv"));
            $this->success = 'Sample Downloaded';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    public function render()
    {
        return view('livewire.admin.business.import-component');
    }
}
