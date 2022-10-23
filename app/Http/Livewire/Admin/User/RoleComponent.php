<?php

namespace App\Http\Livewire\Admin\User;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleComponent extends Component
{
    use WithPagination;

    public $error;

    public $hiddenId = 0;
    public $permissions;
    public $name;
    public $btnType = 'Create';

    // in component
    protected $listeners = ['selectedItem'];

    protected function rules()
    {
        return [
            'name' => 'required|unique:roles,name,'.$this->hiddenId,
            'permissions' => 'required'
        ];
    }

    public function mount()
    {
        $this->permissionList = Permission::all();
    }

    public function render()
    {
        return view('livewire.admin.user.role-component', [
            'roles' => Role::orderBy('created_at', 'desc')->paginate(10),
        ])->layout('layouts.admin');
    }

    // Store
    public function storeRole()
    {
        $this->validate(); // validate User form

        DB::beginTransaction();

        try {
            $updateId = $this->hiddenId;
            if($updateId > 0)
            {
                $role = Role::find($updateId); // update Role
            }
            else{
                $role = new Role(); // create Role
            }
            
            $role->name  = $this->name;
            $role->save();

            $role->syncPermissions($this->permissions);

            DB::commit();

            $this->dispatchBrowserEvent('success-message',['message' => 'Role has been ' . $this->btnType . '.']);

            $this->reset('name', 'permissions', 'hiddenId', 'btnType');

            $this->emit('permissionEvent', $this->permissions);
            
        } catch (\Throwable $th) {
            DB::rollback();
            $this->error = $th->getMessage();
            // $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message',['message' => $this->error]);
        }
    }

    // Update Form
    public function editForm($id)
    {
        $singleRole        = Role::find($id);
        $this->hiddenId    = $singleRole->id;
        $this->name        = $singleRole->name;
        $this->permissions = $singleRole->permissions;
        $this->btnType     = 'Update';
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = Role::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'Role Deleted Successfully']);
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
            $data = Role::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'Role Restored Successfully']);
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
        $this->reset('name', 'permissions', 'hiddenId', 'btnType');
    }
}
