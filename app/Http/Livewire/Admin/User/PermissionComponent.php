<?php

namespace App\Http\Livewire\Admin\User;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class PermissionComponent extends Component
{
    use WithPagination;

    public $error;

    public $hiddenId = 0;
    public $name;
    public $btnType = 'Create';

    protected function rules()
    {
        return [
            'name' => 'required|unique:permissions,name',
        ];
    }

    public function render()
    {
        return view('livewire.admin.user.permission-component', [
            'permissions' => Permission::orderBy('created_at', 'desc')->paginate(10),
        ])->layout('layouts.admin');
    }

    // Store
    public function storePermission()
    {
        $this->validate(); // validate Permission form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $permission = Permission::find($updateId); // update Permission
            }
            else{
                $permission = new Permission(); // create Permission
            }
            
            $permission->name  = $this->name;
            $permission->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message',['message' => 'Permission has been ' . $this->btnType . '.']);

            $this->reset('name', 'hiddenId', 'btnType');
            
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
        $singlePermission = Permission::find($id);
        $this->hiddenId   = $singlePermission->id;
        $this->name       = $singlePermission->name;
        $this->btnType    = 'Update';
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = Permission::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'Permission Deleted Successfully']);
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
            $data = Permission::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'Permission Restored Successfully']);
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
        $this->reset('name', 'hiddenId', 'btnType');
    }
}
