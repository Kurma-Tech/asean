<?php

namespace App\Http\Livewire\Admin\Country;

use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CountryListComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public $error;

    public $hiddenId = 0;
    public $name;
    public $c_code;
    public $short_code;
    public $status = true;
    public $btnType = 'Create';

    protected function rules()
    {
        return [
            'name'       => 'required|min:3',
            'c_code'     => 'nullable|min:3|max:10',
            'short_code' => 'nullable|min:3|max:10',
            'status'     => 'boolean',
        ];
    }

    public function render()
    {
        return view('livewire.admin.country.country-list-component', [
            'countries' => Country::search($this->search)
                ->withTrashed()
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    public function storeCountry()
    {
        $this->validate(); // validate country form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $country = Country::find($updateId); // Update Country
            }
            else{
                $country = new Country(); // Create Country
            }
            
            $country->name         = $this->name;
            $country->c_code       = $this->c_code;
            $country->short_code   = $this->short_code;
            $country->status       = $this->status;
            $country->save();

            DB::commit();
            
            $this->reset('name', 'c_code', 'short_code', 'status', 'hiddenId', 'btnType');

            $this->dispatchBrowserEvent('success-message',['message' => 'Country has been created.']);
            
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // Update Form
    public function editForm($country_id)
    {
        $singleCountry    = Country::find($country_id);
        $this->hiddenId   = $singleCountry->id;
        $this->name       = $singleCountry->name;
        $this->c_code     = $singleCountry->c_code;
        $this->short_code = $singleCountry->short_code;
        $this->status     = $singleCountry->status;
        $this->btnType    = 'Update';
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = Country::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'Country deleted successfully']);
            }else{
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            // $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // restore
    public function restore($id)
    {
        try {
            $data = Country::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'Country restored successfully']);
            }else{
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = $th->getMessage();
            // $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }
}
