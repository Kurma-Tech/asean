<?php

namespace App\Http\Livewire\Admin\Business;

use App\Models\Business;
use App\Models\BusinessGroup;
use App\Models\BusinessType;
use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\IndustryClassification;
use App\Models\Province;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BusinessAddComponent extends Component
{
    public 
        $businessTypes,
        $businessGroups,
        $industryClassifications,
        $countries,
        $regions,
        $provinces,
        $districts,
        $cities;

    public $selectedCountry  = Null;
    public $selectedRegion   = Null;
    public $selectedProvince = Null;
    public $selectedDistrict = Null;

    public
        $company_name,
        $sec_no,
        $business_type_id,
        $business_group_id,
        $industry_classification_id,
        $city_id,
        $year,
        $date_registered,
        $address,
        $long,
        $lat;

    protected $listeners = ['geo_description_changed' => 'geoDescriptionMapping', 'industry_description_changed' => 'industryDescriptionMapping'];

    public function geoDescriptionMapping($geoDescription)
    { // geo description
        $this->geo_description = $geoDescription;
    }

    public function industryDescriptionMapping($industryDescription)
    { // industry description
        $this->industry_description = $industryDescription;
    }

    protected function rules()
    {
        return [
            'company_name'               => 'required',
            'sec_no'                     => 'required',
            'business_type_id'           => 'required|integer',
            'business_group_id'          => 'required|integer',
            'industry_classification_id' => 'required|integer',
            'selectedCountry'            => 'required|integer',
            'selectedRegion'             => 'nullable|integer',
            'selectedProvince'           => 'nullable|integer',
            'selectedDistrict'           => 'nullable|integer',
            'city_id'                    => 'nullable|integer',
            'year'                       => 'required',
            'date_registered'            => 'required|date_format:"m/d/Y"',
            'long'                       => 'required',
            'lat'                        => 'required',
            'address'                    => 'required'
        ];
    }

    protected $messages = [
        'selectedCountry.required'            => 'Country field is required',
        'selectedCountry.integer'             => 'You must select country from drop down',
        'selectedRegion.required'             => 'Region field is required',
        'selectedRegion.integer'              => 'You must select region from drop down',
        'selectedProvince.required'           => 'Province field is required',
        'selectedProvince.integer'            => 'You must select province from drop down',
        'selectedDistrict.required'           => 'District field is required',
        'selectedDistrict.integer'            => 'You must select district from drop down',
        'city_id.required'                    => 'City field is required',
        'city_id.integer'                     => 'You must select city from drop down',
        'business_type_id.required'           => 'Business type field is required',
        'business_type_id.integer'            => 'You must select business type from drop down',
        'business_group_id.required'          => 'Business group field is required',
        'business_group_id.integer'           => 'You must select business group from drop down',
        'industry_classification_id.required' => 'Classification field is required',
        'industry_classification_id.integer'  => 'You must select classification from drop down',
        'long.required'                       => 'Longitude field is required',
        'lat.required'                        => 'Latitude field is required',
    ];

    public function mount()
    {
        $this->businessTypes           = BusinessType::select('id', 'type')->get();
        $this->businessGroups          = BusinessGroup::select('id', 'group')->get();
        $this->industryClassifications = IndustryClassification::select('id', 'classifications')->get();
        $this->countries               = Country::select('id', 'name')->get();
        $this->regions                 = collect();
        $this->provinces               = collect();
        $this->districts               = collect();
        $this->cities                  = collect();
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

    // update province
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

    // update city
    public function updatedSelectedCity($id)
    {
        if (!is_null($id)) {
            $this->cities = City::where('district_id', $id)
            ->select('id', 'name', 'code')
            ->get();
        }
    }

    public function render()
    {
        return view('livewire.admin.business.business-add-component')->layout('layouts.admin');
    }

    // Store
    public function storeBusiness()
    {  
        $this->validate(); // validate Business form

        DB::beginTransaction();

        $date = explode('/',$this->date_registered);

        try {
            $business = new Business(); // Create Business
            $business->company_name               = $this->company_name;
            $business->sec_no                     = $this->sec_no;
            $business->business_type_id           = $this->business_type_id;
            $business->group_id                   = $this->business_group_id;
            $business->industry_classification_id = $this->industry_classification_id;
            $business->country_id                 = $this->selectedCountry;
            $business->region_id                  = $this->selectedRegion;
            $business->province_id                = $this->selectedProvince;
            $business->district_id                = $this->selectedDistrict;
            $business->city_id                    = $this->city_id;
            $business->year                       = $this->year;
            $business->date_registered            = $this->date_registered;
            $business->long                       = $this->long;
            $business->lat                        = $this->lat;
            $business->address                    = $this->address;
            $business->month                      = $date[0];
            $business->day                        = $date[1];
            $business->status                     = 'REGISTERED';
            $business->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message', ['message' => 'Business has been added to the list.']);

            $this->reset();

        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }
}
