<?php

namespace App\Http\Livewire\Admin\Country\Province;

use App\Models\Country;
use App\Models\Province;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProvinceComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public $error;
    public $countries = [];
    public $regions;

    public $hiddenId = 0;
    public $name;
    public $code;
    public $region_id;

    public $selectedCountry = Null;
    public $btnType = 'Create';

    protected $listeners = ['refreshProvinceListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'name'      => 'required',
            'code'      => 'nullable',
            'region_id' => 'required|integer'
        ];
    }

    protected $messages = [
        'region_id.required' => 'Please select region',
        'region_id.integer'  => 'You must select region from drop down',
    ];

    public function mount()
    {
        $this->countries = Country::select('id', 'name')->get();
        $this->regions   = collect();
    }

    // update Regions
    public function updatedSelectedCountry($id)
    {
        if (!is_null($id)) {
            $this->regions = Region::where('country_id', $id)
            ->select('id', 'name', 'code')
            ->get();
        }
    }

    public function render()
    {
        return view('livewire.admin.country.province.province-component', [
            'provinces' => Province::search($this->search)
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    public function storeProvince()
    {
        $this->validate(); // validate province form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $province = Province::find($updateId); // Update Province
            }
            else{
                $province = new Province(); // Create Province
            }
            
            $province->name      = $this->name;
            $province->code      = $this->code;
            $province->region_id = $this->region_id;
            $province->save();

            DB::commit();
            
            $this->resetFields();

            $this->dispatchBrowserEvent('success-message',['message' => 'Province has been created.']);
            
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
        $singleProvince        = Province::find($id);
        $this->hiddenId        = $singleProvince->id;
        $this->name            = $singleProvince->name;
        $this->code            = $singleProvince->code;
        $this->region_id       = $singleProvince->region_id;
        $this->selectedCountry = $singleProvince->regions->country->id;
        $this->btnType         = 'Update';

        $this->updatedSelectedCountry($this->selectedCountry);
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = Province::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'Province deleted successfully']);
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
            $data = Province::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'Province restored successfully']);
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
        $this->reset('name', 'code', 'region_id', 'hiddenId', 'btnType', 'selectedCountry');
    }
}
