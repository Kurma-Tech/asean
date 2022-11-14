<?php

namespace App\Http\Livewire\Admin\Country\Zip;

use App\Models\Area;
use App\Models\Country;
use App\Models\Zip;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ZipComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public $error;
    public $countries = [];
    public $areas;

    public $hiddenId = 0;
    public $zip_code;
    public $area_id;

    public $selectedCountry = Null;
    public $btnType = 'Create';

    protected $listeners = ['refreshZipCodelListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'zip_code'  => 'required',
            'area_id' => 'required'
        ];
    }

    public function mount()
    {
        $this->countries = Country::select('id', 'name')->get();
        $this->areas = collect();
    }

    // update division
    public function updatedSelectedCountry($id)
    {
        if (!is_null($id)) {
            $this->areas = Area::where('country_id', $id)
            ->select('id', 'area_name', 'area_code')
            ->get();
        }
    }

    public function render()
    {
        return view('livewire.admin.country.zip.zip-component', [
            'zips' => Zip::search($this->search)
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    public function storeArea()
    {
        $this->validate(); // validate zip form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $zip = Zip::find($updateId); // Update Zip
            }
            else{
                $zip = new Zip(); // Create Zip
            }
            
            $zip->zip_code = $this->zip_code;
            $zip->area_id  = $this->area_id;
            $zip->save();

            DB::commit();
            
            $this->resetFields();

            $this->dispatchBrowserEvent('success-message',['message' => 'Zip code has been created.']);
            
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
        $singleZip      = Zip::find($id);
        $this->hiddenId = $singleZip->id;
        $this->zip_code = $singleZip->zip_code;
        $this->area_id  = $singleZip->area_id;
        $this->btnType  = 'Update';
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = Zip::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'Zip code deleted successfully']);
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
            $data = Zip::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'Zip code restored successfully']);
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
        $this->reset('zip_code', 'area_id', 'hiddenId', 'btnType', 'selectedCountry');
    }
}
