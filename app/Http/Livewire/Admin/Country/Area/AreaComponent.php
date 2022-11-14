<?php

namespace App\Http\Livewire\Admin\Country\Area;

use App\Models\Area;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AreaComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public $error;
    public $countries = [];

    public $hiddenId = 0;
    public $area_name;
    public $area_code;
    public $country_id;
    public $btnType = 'Create';

    protected $listeners = ['refreshAreaCodelListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'area_name'  => 'required',
            'area_code'  => 'required',
            'country_id' => 'required'
        ];
    }

    public function mount()
    {
        $this->countries = Country::select('id', 'name')->get();
    }

    public function render()
    {
        return view('livewire.admin.country.area.area-component', [
            'areas' => Area::search($this->search)
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    public function storeArea()
    {
        $this->validate(); // validate area form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $area = Area::find($updateId); // Update Area
            }
            else{
                $area = new Area(); // Create Area
            }
            
            $area->area_name  = $this->area_name;
            $area->area_code  = $this->area_code;
            $area->country_id = $this->country_id;
            $area->save();

            DB::commit();
            
            $this->resetFields();

            $this->dispatchBrowserEvent('success-message',['message' => 'Area code has been created.']);
            
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
        $singleArea       = Area::find($id);
        $this->hiddenId   = $singleArea->id;
        $this->area_name  = $singleArea->area_name;
        $this->area_code  = $singleArea->area_code;
        $this->country_id = $singleArea->country_id;
        $this->btnType    = 'Update';
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = Area::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'Area code deleted successfully']);
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
            $data = Area::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'Area code restored successfully']);
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
        $this->reset('area_name', 'area_code', 'country_id', 'hiddenId', 'btnType');
    }
}
