<?php

namespace App\Http\Livewire\Admin\Country;

use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CountryAddComponent extends Component
{
    public $name;
    public $c_code;
    public $short_code;
    public $status = true;
    public $error;

    protected function rules()
    {
        return [
            'name'       => 'required|min:3',
            'c_code'     => 'nullable|min:3|max:10',
            'short_code' => 'nullable|min:3|max:10',
            'status'     => 'boolean',
        ];
    }

    public function storeCountry()
    {
        $this->validate(); // validate country form

        DB::beginTransaction();

        try {
            // create country
            $country               = new Country();
            $country->name         = $this->name;
            $country->c_code       = $this->c_code;
            $country->short_code   = $this->short_code;
            $country->status       = $this->status;
            $country->save();

            DB::commit();
            
            $this->reset();

            $this->dispatchBrowserEvent('success-message',['message' => 'Country has been created.']);
            
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    public function render()
    {
        return view('livewire.admin.country.country-add-component')->layout('layouts.admin');
    }
}
