<?php

namespace App\Http\Livewire\Admin\Patent;

use App\Models\Country;
use App\Models\Patent;
use App\Models\PatentCategory;
use App\Models\PatentKind;
use App\Models\PatentType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PatentListComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public $error;

    public 
        $countries = [],
        $patentKinds = [],
        $patentTypes = [],
        $categories = [];

    public $hiddenId = 0;
    public $title;
    public $abstract;
    public $filing_no;
    public $applicant_company;
    public $inventor_name;
    public $registration_no;
    public $registration_date;
    public $publication_date;
    public $filing_date;
    public $country_id;
    public $kind_id;
    public $type_id;
    public $long;
    public $lat;
    public $btnType = 'Create';

    protected $listeners = ['refreshPatentListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'title'             => 'required',
            'filing_no'         => 'required',
            'abstract'          => 'nullable',
            'inventor_name'     => 'required',
            'applicant_company' => 'required',
            'country_id'        => 'required|integer',
            'categories'        => 'required|integer',
            'kind_id'           => 'required|integer',
            'type_id'           => 'required|integer',
            'registration_no'   => 'required',
            'registration_date' => 'required|date_format:"m/d/Y"',
            'filing_date'       => 'required|date_format:"m/d/Y"',
            'publication_date'  => 'required|date_format:"m/d/Y"',
            'long'              => 'nullable',
            'lat'               => 'nullable',
        ];
    }

    protected $messages = [
        'country_id.required'    => 'Country field is required',
        'country_id.integer'     => 'You must select country from drop down',
        'categories.required'    => 'Category field is required',
        'categories.integer'     => 'You must select country from drop down',
        'kind_id.required'       => 'Patent kind field is required',
        'kind_id.integer'        => 'You must select patent kind from drop down',
        'type_id.required'       => 'Patent type field is required',
        'type_id.integer'        => 'You must select patent type from drop down',
    ];

    public function mount()
    {
        $this->countries        = Country::select('id', 'name')->get();
        $this->patentCategories = PatentCategory::where('class_id', '!=', Null)->select('ipc_code')->get();
        $this->patentKinds      = PatentKind::select('id', 'kind')->get();
        $this->patentTypes      = PatentType::select('id', 'type')->get();
    }

    public function render()
    {
        return view('livewire.admin.patent.patent-list-component', [
            'patents' => Patent::search($this->search)
                ->orderBy($this->orderBy, $this->sortBy ? 'asc' : 'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    // Store
    public function storePatent()
    {
        $this->validate(); // validate Patent form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if ($updateId > 0) {
                $patent = Patent::find($updateId); // update Patent
            } else {
                $patent = new Patent(); // create Patent
            }

            $date = explode('/', $this->filing_date);

            $patent->title             = $this->title;
            $patent->filing_no         = $this->filing_no;
            $patent->applicant_company = $this->applicant_company;
            $patent->registration_no   = $this->registration_no;
            $patent->country_id        = $this->country_id;
            $patent->kind_id           = $this->kind_id;
            $patent->type_id           = $this->type_id;
            $patent->registration_date = $this->registration_date;
            $patent->publication_date  = $this->publication_date;
            $patent->filing_date       = $this->filing_date;
            $namesToArray              = explode(';', $this->inventor_name);
            $namesJson                 = json_encode($namesToArray);
            $patent->inventor_name     = $namesJson;
            $patent->long              = $this->long;
            $patent->lat               = $this->lat;
            $patent->abstract          = $this->abstract;
            $patent->month             = $date[0];
            $patent->year              = $date[2];
            $patent->save();

            $patent->patentCategories()->sync($this->patents);

            DB::commit();

            $this->dispatchBrowserEvent('success-message', ['message' => 'Intellectual Property Has Been ' . $this->btnType . '.']);

            $this->resetFields();
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }

    // Update Form
    public function editForm($id)
    {
        $singlePatent             = Patent::find($id);
        $this->hiddenId           = $singlePatent->id;
        $this->title              = $singlePatent->title;
        $this->country_id         = $singlePatent->country_id;
        $this->patents            = $singlePatent->patents;
        $this->kind_id            = $singlePatent->kind_id;
        $this->type_id            = $singlePatent->type_id;
        $this->registration_date  = $singlePatent->registration_date;
        $this->publication_date   = $singlePatent->publication_date;
        $this->filing_date        = $singlePatent->filing_date;
        $this->filing_no          = $singlePatent->filing_no;
        $this->regirstration_no   = $singlePatent->regirstration_no;
        $this->inventor_name      = implode(';',json_decode($singlePatent->inventor_name));
        $this->applicant_company  = $singlePatent->applicant_company;
        $this->long               = $singlePatent->long;
        $this->lat                = $singlePatent->lat;
        $this->btnType            = 'Update';

        $this->emit('countryEvent', $this->country_id);
        $this->emit('typeEvent', $this->kind_id);
        $this->emit('kindEvent', $this->kind_id);
        $this->emit('categoryEvent', $this->category_id);
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = Patent::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message', ['message' => 'Intellectual Property Deleted Successfully']);
            } else {
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = 'Ops! looks like we had some problem';
            // $this->error = $th->getMessage();
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }

    // restore
    public function restore($id)
    {
        try {
            $data = Patent::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message', ['message' => 'Intellectual Property Restored Successfully']);
            } else {
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }

    // reset fields
    public function resetFields()
    {
        $this->reset('title', 'filing_no', 'registration_no', 'country_id', 'patents', 'kind_id', 'type_id', 'registration_date', 'publication_date', 'filing_date', 'inventor_name', 'long', 'lat', 'abstract', 'hiddenId', 'btnType');
    }
}
