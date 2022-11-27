<?php

namespace App\Http\Livewire\Admin\Country\Region;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class RegionComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public $error;
    public $countries = [];

    public $hiddenId = 0;
    public $name;
    public $code;
    public $country_id;
    public $btnType = 'Create';

    protected $listeners = ['refreshRegionListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'name'       => 'required',
            'code'       => 'required',
            'country_id' => 'required|integer'
        ];
    }

    protected $messages = [
        'country_id.required' => 'Please select country',
        'country_id.integer'  => 'You must select country from drop down',
    ];

    public function mount()
    {
        $this->countries = Country::select('id', 'name')->get();
    }

    public function render()
    {
        return view('livewire.admin.country.region.region-component', [
            'regions' => Region::search($this->search)
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    public function storeRegion()
    {
        $this->validate(); // validate region form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $region = Region::find($updateId); // Update Region
            }
            else{
                $region = new Region(); // Create Region
            }
            
            $region->name       = $this->name;
            $region->code       = $this->code;
            $region->country_id = $this->country_id;
            $region->save();

            DB::commit();
            
            $this->resetFields();

            $this->dispatchBrowserEvent('success-message',['message' => 'Region has been created.']);
            
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
        $singleRegion     = Region::find($id);
        $this->hiddenId   = $singleRegion->id;
        $this->name       = $singleRegion->name;
        $this->code       = $singleRegion->code;
        $this->country_id = $singleRegion->country_id;
        $this->btnType    = 'Update';
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = Region::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'Region deleted successfully']);
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
            $data = Region::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'Region restored successfully']);
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
        $this->reset('name', 'code', 'country_id', 'hiddenId', 'btnType');
    }
}
