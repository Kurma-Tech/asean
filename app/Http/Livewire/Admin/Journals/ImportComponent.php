<?php

namespace App\Http\Livewire\Admin\Journals;

use App\Imports\JournalImport;
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

    public function render()
    {
        return view('livewire.admin.journals.import-component');
    }

    public function journalImport()
    {
        $this->validate();

        DB::beginTransaction();

        try {

            Excel::import(new JournalImport, $this->file);

            DB::commit();
            
            $this->reset();
            $this->success = 'Journal Imported Successfully';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
            $this->emit('refreshJournalListComponent');

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
            $this->success = 'Journal Sample Downloaded';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            // $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }
}
