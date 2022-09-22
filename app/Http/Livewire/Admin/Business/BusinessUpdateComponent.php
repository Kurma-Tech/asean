<?php

namespace App\Http\Livewire\Admin\Business;

use App\Models\Business;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BusinessUpdateComponent extends Component
{
    public
        $business_id,
        $company_name,
        $sec_no,
        $ngc_code,
        $business_type_id,
        $industry_classification_id,
        $country_id,
        $year,
        $date_registered,
        $geo_code,
        $industry_code,
        $geo_description,
        $industry_description,
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
            'ngc_code'                   => 'nullable',
            'business_type_id'           => 'required|integer',
            'industry_classification_id' => 'required|integer',
            'country_id'                 => 'required|integer',
            'year'                       => 'required',
            'date_registered'            => 'required|date_format:"m/d/Y"',
            'geo_code'                   => 'nullable',
            'industry_code'              => 'nullable',
            'geo_description'            => 'nullable',
            'industry_description'       => 'nullable',
            'long'                       => 'required',
            'lat'                        => 'required',
            'address'                    => 'required'
        ];
    }

    protected $messages = [
        'country_id.required'                 => 'Country field is required',
        'country_id.integer'                  => 'You must select country from drop down',
        'business_type_id.required'           => 'Business type field is required',
        'business_type_id.integer'            => 'You must select business type from drop down',
        'industry_classification_id.required' => 'Classification field is required',
        'industry_classification_id.integer'  => 'You must select classification from drop down',
        'long.required'                       => 'Longitude field is required',
        'lat.required'                        => 'Latitude field is required'
    ];

    public function mount($key)
    {
        $this->business_id                = $key;
        $business                         = Business::findOrFail($this->business_id);
        $this->company_name               = $business->company_name;
        $this->sec_no                     = $business->sec_no;
        $this->ngc_code                   = $business->ngc_code;
        $this->business_type_id           = $business->business_type_id;
        $this->industry_classification_id = $business->industry_classification_id;
        $this->country_id                 = $business->country_id;
        $this->year                       = $business->year;
        $this->date_registered            = $business->date_registered;
        $this->geo_code                   = $business->geo_code;
        $this->industry_code              = $business->industry_code;
        $this->geo_description            = $business->geo_description;
        $this->industry_description       = $business->industry_description;
        $this->address                    = $business->address;
        $this->long                       = $business->long;
        $this->lat                        = $business->lat;
    }

    public function render()
    {
        $countries               = DB::table('countries')->select('id', 'name')->get();
        $businessTypes           = DB::table('business_types')->select('id', 'type')->get();
        $industryClassifications = DB::table('industry_classifications')->select('id', 'classifications')->get();

        return view('livewire.admin.business.business-update-component', [
            'countries' => $countries, 
            'businessTypes' => $businessTypes, 
            'industryClassifications' => $industryClassifications
        ])->layout('layouts.admin');
    }

    // Update
    public function updateBusiness()
    {  
        $this->validate(); // validate Business form

        DB::beginTransaction();

        try {
            $business = Business::findOrFail($this->business_id);
            $business->company_name               = $this->company_name;
            $business->sec_no                     = $this->sec_no;
            $business->ngc_code                   = $this->ngc_code;
            $business->business_type_id           = $this->business_type_id;
            $business->industry_classification_id = $this->industry_classification_id;
            $business->country_id                 = $this->country_id;
            $business->year                       = $this->year;
            $business->date_registered            = $this->date_registered;
            $business->geo_code                   = $this->geo_code;
            $business->industry_code              = $this->industry_code;
            $business->geo_description            = $this->geo_description;
            $business->industry_description       = $this->industry_description;
            $business->long                       = $this->long;
            $business->lat                        = $this->lat;
            $business->address                    = $this->address;
            $business->status                     = 'REGISTERED';
            $business->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message', ['message' => 'Business has been added to the list.']);

        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }
}
