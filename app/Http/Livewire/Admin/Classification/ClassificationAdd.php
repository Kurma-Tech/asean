<?php

namespace App\Http\Livewire\Admin\Classification;

use App\Models\IndustryClassification;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClassificationAdd extends Component
{
    use WithFileUploads;

    public $fileHasHeader = true;
    public $csv_file;
    public $csv_data = [];
    public $db_fields = [];
    public $csv_header_cols = [];
    public $match_fields;
    public $data;
    public $failed = [];
    public $imported = false;

    public function rules()
    {
        return ['csv_file' => 'required|file'];
    }

    public function render()
    {
        return view('livewire.admin.classification.classification-add')->layout('layouts/admin');
    }

    function parseFile()
    {
        $cols = Schema::getColumnListing('industry_classifications');

        $this->db_fields = array_diff($cols, ['id', 'created_at', 'updated_at', 'deleted_at']);
        array_unshift($this->db_fields,'Skip');

        $path = $this->csv_file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $this->data = $data;

        if (count($data) > 0) {
            $this->csv_header_cols = [];
            $this->match_fields = [];

            if ($this->fileHasHeader) {
                foreach ($data[0] as $key => $value) {
                    $this->csv_header_cols[] = $key;
                    $this->match_fields[] = $value;
                }
                $this->csv_data = array_slice($data, 0, 2);
            } else {
                $this->csv_data = array_slice($data, 0, 1);
            }
        } else {
            $this->emit('error', 'No data found in your file');
        }
        // $this->match_fields = $this->csv_header_cols;
    }

    function processImport()
    {

        // error_log(json_encode($this->match_fields));
        // error_log(json_encode($this->csv_header_cols));
        // if (empty($this->match_fields) || count($this->match_fields) < count($this->csv_header_cols)) {
        //     $this->emit('error', __("All columns must be matched"));
        //     return;
        // }

        $errors = [];

        foreach ($this->data as $key => $row) {
            if ($this->fileHasHeader && $key == 0) continue;
            $classification = new IndustryClassification();
            // if (empty($this->csv_header_cols)) {
            //     foreach ($this->match_fields as $k => $mf) {
            //         $this->csv_header_cols[$mf] = $k;
            //     }
            // }

            foreach ($this->csv_header_cols as $header_col) {
                // dd($this->match_fields);
                $field = $this->match_fields[$header_col]??null;
                
                if(is_null($field)) continue;

                $value = $row[$header_col];

                if ($field == "Skip") continue;
                if (empty($field)) continue; //skip headings
                // error_log($value);
                $slug = SlugService::createSlug(IndustryClassification::class, 'slug', $value, ['unique' => false]);
                $classification->$field = $value;
                $classification->slug = $slug;
            }
            // try {
                $classification->save();
            // } catch (\Exception $e) {
            //     $errors[] = $row;
            //     return;
            // }
        }  
        if (empty($errors)) {

            $this->csv_file = null;
            $this->csv_data = [];
            $this->db_fields = [];
            $this->csv_header_cols = [];
            $this->match_fields = null;
            $this->data = null;
            $this->failed = [];
            $this->imported = true;
            $this->emit('success', __('Contacts imported'));
            $this->emit('confetti');
        } else {
            $this->failed = $errors;
            $this->emit('error', 'Error saving some records');
        }
    }
}
