<?php

namespace App\Http\Livewire\Admin\Country\Province;

use App\Imports\ProvinceImport;
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
        return view('livewire.admin.country.province.import-component');
    }

    public function provinceImport()
    {
        $this->validate([ 
            'file' => 'required'
        ]);
        ini_set('memory_limit', -1);
        DB::beginTransaction();

        try {
            set_time_limit(0);
            Excel::import(new ProvinceImport, $this->file);

            DB::commit();
            
            $this->reset();
            $this->success = 'Province Imported Successfully';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
            $this->emit('refreshProvinceListComponent');

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
            return response()->download(storage_path("app\public\journal-import-sample.csv"));
            $this->success = 'Province Sample Downloaded';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            // $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }
}
