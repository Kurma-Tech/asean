<?php

namespace App\Http\Livewire\Admin\Country\City;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\Province;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CityComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public $error;
    public $countries = [];
    public $regions;
    public $provinces;
    public $districts;

    public $hiddenId = 0;
    public $name;
    public $code;
    public $district_id;

    public $selectedCountry  = Null;
    public $selectedRegion   = Null;
    public $selectedProvince = Null;
    public $btnType = 'Create';

    protected $listeners = ['refreshCityListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'name'        => 'required',
            'code'        => 'required',
            'district_id' => 'required'
        ];
    }

    protected $messages = [
        'district_id.required' => 'Please select district',
        'district_id.integer'  => 'You must select district from drop down',
    ];

    public function mount()
    {
        $this->countries = Country::select('id', 'name')->get();
        $this->regions = collect();
    }

    // update regions
    public function updatedSelectedCountry($id)
    {
        if (!is_null($id)) {
            $this->regions = Region::where('country_id', $id)
            ->select('id', 'name', 'code')
            ->get();
        }
    }

    // update provinces
    public function updatedSelectedRegion($id)
    {
        if (!is_null($id)) {
            $this->provinces = Province::where('region_id', $id)
            ->select('id', 'name', 'code')
            ->get();
        }
    }

    // update district
    public function updatedSelectedProvince($id)
    {
        if (!is_null($id)) {
            $this->districts = District::where('province_id', $id)
            ->select('id', 'name', 'code')
            ->get();
        }
    }

    public function render()
    {
        return view('livewire.admin.country.city.city-component', [
            'cities' => City::search($this->search)
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    public function storeCity()
    {
        $this->validate(); // validate city form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $city = City::find($updateId); // Update City
            }
            else{
                $city = new City(); // Create City
            }
            
            $city->name        = $this->name;
            $city->code        = $this->code;
            $city->district_id = $this->district_id;
            $city->save();

            DB::commit();
            
            $this->resetFields();

            $this->dispatchBrowserEvent('success-message',['message' => 'City has been created.']);
            
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // Update Form
    public function editForm($id)
    {
        $singleCity            = City::find($id);
        $this->hiddenId        = $singleCity->id;
        $this->name            = $singleCity->name;
        $this->code            = $singleCity->code;
        $this->district_id     = $singleCity->district_id;
        $this->selectedCountry = $singleCity->districts->provinces->regions->country->id;
        $this->selectedRegion  = $singleCity->districts->provinces->regions->id;
        $this->selectedRegion  = $singleCity->districts->provinces->id;
        $this->btnType         = 'Update';

        $this->updatedSelectedCountry($this->selectedCountry);
        $this->updatedSelectedRegion($this->selectedRegion);
        $this->updatedSelectedProvince($this->selectedProvince);
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = Province::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'City deleted successfully']);
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
            $data = City::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'City restored successfully']);
            }else{
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // reset fields
    public function resetFields()
    {
        $this->reset('name', 'code', 'region_id', 'hiddenId', 'btnType', 'selectedCountry', 'selectedRegion', 'selectedProvince');
    }
}
