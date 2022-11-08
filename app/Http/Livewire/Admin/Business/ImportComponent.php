<?php

namespace App\Http\Livewire\Admin\Business;

use App\Imports\BusinessImport;
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

    public function businessImport()
    {
        $this->validate();
        ini_set('memory_limit', -1);
        DB::beginTransaction();

        try {
            set_time_limit(0);

            Excel::import(new BusinessImport, $this->file);

            DB::commit();
            
            $this->reset();
            $this->success = 'Business Data Imported Successfully';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);

            return redirect(request()->header('Referer'));
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
