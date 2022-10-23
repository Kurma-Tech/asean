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

    protected $listeners = ['refreshUserListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'name' => 'required|unique:permissions,name',
        ];
    }

    public function render()
    {
        return view('livewire.admin.user.permission-component', [
            'permission' => Permission::orderBy('created_at', 'desc')->paginate(10),
        ])->layout('layouts.admin');
    }

    // Store
    public function storeUser()
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

            DB::commit();

            $this->dispatchBrowserEvent('success-message',['message' => 'Role has been ' . $this->btnType . '.']);

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
        $singleRole     = Role::find($id);
        $this->hiddenId = $singleRole->id;
        $this->name     = $singleRole->name;
        $this->btnType  = 'Update';
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
        $this->reset('name', 'hiddenId', 'btnType');
    }
}
