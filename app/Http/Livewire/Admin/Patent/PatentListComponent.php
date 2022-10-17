<?php

namespace App\Http\Livewire\Admin\Patent;

use App\Models\Country;
use App\Models\Patent;
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
        $patentTypes = [];

    public $hiddenId = 0;
    public $title;
    public $patent_id;
    public $country_id;
    public $kind_id;
    public $type_id;
    public $date;
    public $long;
    public $lat;
    public $btnType = 'Create';

    protected $listeners = ['refreshPatentListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'title'      => 'required',
            'patent_id'  => 'required',
            'country_id' => 'required|integer',
            'kind_id'    => 'required|integer',
            'type_id'    => 'required|integer',
            'date'       => 'required|date_format:"m/d/Y"',
            'long'       => 'required',
            'lat'        => 'required',
        ];
    }

    protected $messages = [
        'country_id.required' => 'Country field is required',
        'country_id.integer'  => 'You must select country from drop down',
        'kind_id.required'    => 'Patent kind field is required',
        'kind_id.integer'     => 'You must select patent kind from drop down',
        'type_id.required'    => 'Patent type field is required',
        'type_id.integer'     => 'You must select patent type from drop down',
        'long.required'       => 'Longitude field is required',
        'lat.required'        => 'Latitude field is required',
    ];

    public function mount()
    {
        $this->countries   = Country::select('id', 'name')->get();
        $this->patentKinds = PatentKind::select('id', 'kind')->get();
        $this->patentTypes = PatentType::select('id', 'type')->get();
    }

    public function render()
    {
        return view('livewire.admin.patent.patent-list-component', [
            'patents' => Patent::search($this->search)
                ->withTrashed()
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

            $patent->title      = $this->title;
            $patent->patent_id  = $this->patent_id;
            $patent->country_id = $this->country_id;
            $patent->kind_id    = $this->kind_id;
            $patent->type_id    = $this->type_id;
            $patent->date       = $this->date;
            $patent->long       = $this->long;
            $patent->lat        = $this->lat;
            $patent->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message', ['message' => 'Patent has been ' . $this->btnType . '.']);

            $this->reset('title', 'patent_id', 'country_id', 'kind_id', 'type_id', 'date', 'long', 'lat', 'hiddenId', 'btnType');
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = $th->getMessage();
            // $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }

    // Update Form
    public function editForm($id)
    {
        $singlePatent     = Patent::find($id);
        $this->hiddenId   = $singlePatent->id;
        $this->title      = $singlePatent->title;
        $this->patent_id  = $singlePatent->patent_id;
        $this->country_id = $singlePatent->country_id;
        $this->kind_id    = $singlePatent->kind_id;
        $this->type_id    = $singlePatent->type_id;
        $this->date       = $singlePatent->date;
        $this->long       = $singlePatent->long;
        $this->lat        = $singlePatent->lat;
        $this->btnType    = 'Update';

        $this->emit('countryEvent', $this->country_id);
        $this->emit('typeEvent', $this->kind_id);
        $this->emit('kindEvent', $this->kind_id);
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = Patent::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message', ['message' => 'Patent deleted successfully']);
            } else {
                $this->error = 'Ops! looks like we had some problem';
                $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = 'Ops! looks like we had some problem';
            $this->error = $th->getMessage();
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
                $this->dispatchBrowserEvent('success-message', ['message' => 'Patent restored successfully']);
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
        $this->reset('title', 'patent_id', 'country_id', 'kind_id', 'type_id', 'date', 'long', 'lat', 'hiddenId', 'btnType');
    }
}
