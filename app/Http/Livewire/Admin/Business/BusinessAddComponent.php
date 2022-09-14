<?php

namespace App\Http\Livewire\Admin\Business;

use App\Models\Business;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BusinessAddComponent extends Component
{
    public $company_name;
    public $sec_no;
    public $ngc_code;
    public $business_type_id;
    public $industry_classification_id;
    public $year;
    public $date_registerd;
    public $geo_code;
    public $industry_code;
    public $geo_description;
    public $industry_description;
    public $address;
    public $long;
    public $lat;

    protected $listeners = ['geo_description_changed' => 'geoDescriptionMapping', 'industry_description_changed' => 'industryDescriptionMapping'];

    public function geoDescriptionMapping($geoDescription){ // geo description
        $this->geo_description = $geoDescription;
    }

    public function industryDescriptionMapping($industryDescription){ // industry description
        $this->industry_description = $industryDescription;
    }

    protected function rules()
    {
        return [
            'company_name'               => 'required',
            'sec_no'                     => 'required',
            'ngc_code'                   => 'nullable',
            'business_type_id'           => 'required',
            'industry_classification_id' => 'required',
            'year'                       => 'required',
            'date_registerd'             => 'required',
            'geo_code'                   => 'nullable',
            'industry_code'              => 'nullable',
            'geo_description'            => 'nullable',
            'industry_description'       => 'nullable',
            'long'                       => 'required',
            'lat'                        => 'required',
            'address'                    => 'required'
        ];
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

        try {
            $business = new Business(); // Create Business
            $business->company_name               = $this->company_name;
            $business->sec_no                     = $this->sec_no;
            $business->ngc_code                   = $this->ngc_code;
            $business->business_type_id           = $this->business_type_id;
            $business->industry_classification_id = $this->industry_classification_id;
            $business->year                       = $this->year;
            $business->date_registerd             = $this->date_registerd;
            $business->geo_code                   = $this->geo_code;
            $business->industry_code              = $this->industry_code;
            $business->geo_description            = $this->geo_description;
            $business->industry_description       = $this->industry_description;
            $business->long                       = $this->long;
            $business->lat                        = $this->lat;
            $business->address                    = $this->address;
            $business->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message',['message' => 'Business has been added to the list.']);

            $this->reset();
            
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }
}
