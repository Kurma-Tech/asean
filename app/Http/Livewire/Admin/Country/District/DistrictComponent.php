<?php

namespace App\Http\Livewire\Admin\Country\District;

use App\Models\Country;
use App\Models\District;
use App\Models\Province;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class DistrictComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public $error;
    // public $regions;
    // public $provinces;

    public $hiddenId = 0;
    public $name;
    public $code;
    public $province_id;

    public $selectedCountry = Null;
    public $selectedRegion  = Null;
    public $btnType = 'Create';

    protected $listeners = ['refreshDistrictListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'name'        => 'required',
            'code'        => 'nullable',
            'province_id' => 'required|integer'
        ];
    }

    protected $messages = [
        'province_id.required' => 'Please select province',
        'province_id.integer'  => 'You must select province from drop down',
    ];

    public function mount()
    {
        $this->regions   = collect();
        $this->provinces = collect();
    }

    // update Regions
    // public function updatedSelectedCountry($id)
    // {
    //     if (!is_null($id)) {
    //         $this->regions = Region::where('country_id', $id)
    //         ->select('id', 'name', 'code')
    //         ->get();
    //     }
    // }

    // // update Provinces
    // public function updatedSelectedRegion($id)
    // {
    //     if (!is_null($id)) {
    //         $this->provinces = Province::where('region_id', $id)
    //         ->select('id', 'name', 'code')
    //         ->get();
    //     }
    // }

    public function render()
    {
        $countries = [];
        $regions = [];
        $provinces = [];
        $countries = Country::select('id', 'name')->get();
        if($this->selectedCountry != null){
            $regions = Region::where('country_id', $this->selectedCountry)
            ->select('id', 'name', 'code')
            ->get();
        }
        if($this->selectedRegion != null){
            $provinces = Province::where('region_id', $this->selectedRegion)
            ->select('id', 'name', 'code')
            ->get();
        }
        
        return view('livewire.admin.country.district.district-component', [
            'districts' => District::search($this->search)
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
            'regions'   => $regions,
            'provinces' => $provinces,
            'countries' =>  $countries
        ])->layout('layouts.admin');
    }

    public function storeDistrict()
    {
        $this->validate(); // validate district form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $district = District::find($updateId); // Update District
            }
            else{
                $district = new District(); // Create District
            }
            
            $district->name        = $this->name;
            $district->code        = $this->code;
            $district->province_id = $this->province_id;
            $district->save();

            DB::commit();
            
            $this->resetFields();

            $this->dispatchBrowserEvent('success-message',['message' => 'District has been created.']);
            
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
        $singleDistrict        = District::find($id);
        $this->hiddenId        = $singleDistrict->id;
        $this->name            = $singleDistrict->name;
        $this->code            = $singleDistrict->code;
        $this->province_id     = $singleDistrict->province_id;
        $this->selectedCountry = $singleDistrict->provinces->regions->country->id;
        $this->selectedRegion  = $singleDistrict->provinces->regions->id;
        $this->btnType         = 'Update';

        // $this->updatedSelectedCountry($this->selectedCountry);
        // $this->updatedSelectedRegion($this->selectedRegion);
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = District::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'District deleted successfully']);
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
            $data = District::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'District restored successfully']);
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
        $this->reset('name', 'code', 'district_id', 'hiddenId', 'btnType', 'selectedCountry', 'selectedRegion');
    }
}
