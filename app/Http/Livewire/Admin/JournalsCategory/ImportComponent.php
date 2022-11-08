<?php

namespace App\Http\Livewire\Admin\JournalsCategory;

use App\Imports\JournalCategoryImport;
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

    public function dataImport()
    {
        $this->validate();
        ini_set('memory_limit', -1);
        DB::beginTransaction();
        try {
            Excel::import(new JournalCategoryImport, $this->file);

            DB::commit();
            
            $this->reset();
            $this->success = 'Journal category data imported successfully';
            $this->dispatchBrowserEvent('success-message',['message' => $this->success]);
            $this->emit('refreshJournalCategoryListComponent');

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
            return response()->download(storage_path("app\public\journal-category-import-sample.csv"));
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
        return view('livewire.admin.journals-category.import-component');
    }
}
