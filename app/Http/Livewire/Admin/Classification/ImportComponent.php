<?php

namespace App\Http\Livewire\Admin\Classification;

use App\Imports\ClassificationImport;
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
            'file' => 'required|mimes:xlsx,xls,csv,txt'
        ];
    }

    public function dataImport()
    {
        $this->validate();
        ini_set('memory_limit', -1);
        DB::beginTransaction();
        try {
            Excel::import(new ClassificationImport, $this->file);

            DB::commit();
            
            $this->reset();
            $this->success = 'Classification Data Imported Successfully';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
            $this->emit('refreshClassificationListComponent');

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
            return response()->download(storage_path("app\public\classification-import-sample.csv"));
            $this->success = 'Sample Downloaded';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            // $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    public function render()
    {
        return view('livewire.admin.classification.import-component');
    }
}
