<?php

namespace App\Http\Livewire\Admin\User;

use App\Mail\UserAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class UserListComponent extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $orderBy = 'id';
    public $sortBy = false;

    public $error;
    public $random_password;

    public $hiddenId = 0;
    public $name;
    public $email;
    public $btnType = 'Create';

    protected $listeners = ['refreshUserListComponent' => '$refresh'];

    protected function rules()
    {
        return [
            'name'  => 'required',
            'email' => 'required|unique:users,email',
        ];
    }

    public function render()
    {
        return view('livewire.admin.user.user-list-component', [
            'users' => User::search($this->search)
                ->where('is_admin', '!=', 1)
                ->withTrashed()
                ->orderBy($this->orderBy, $this->sortBy ? 'asc':'desc')
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }

    public function randomPasswordGenerator($limit)
    {
        $password = '';
        $passwordSets = ['1234567890', '@#', 'ABCDEFGHJKLMNPQRSTUVWXYZ', 'abcdefghjkmnpqrstuvwxyz'];

        //Get random character from the array
        foreach ($passwordSets as $passwordSet) {
            $password .= $passwordSet[array_rand(str_split($passwordSet))];
        }

        // 9 is the length of password we want
        while (strlen($password) < $limit) {
            $randomSet = $passwordSets[array_rand($passwordSets)];
            $password .= $randomSet[array_rand(str_split($randomSet))];
        }

        // To sent on Email
        $this->random_password = $password;

        //Hash Generated Password
        $password = Hash::make($password);

        return $password;
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
                $user = User::find($updateId); // update User
            }
            else{
                $user = new User(); // create User
            }
            $password = Str::random(9);
            $user->name     = $this->name;
            $user->email    = $this->email;
            $user->password = $this->randomPasswordGenerator(9);
            $user->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message',['message' => 'User has been ' . $this->btnType . '.']);

            $context = [
                "user_name" => $user->name,
                "user_email" => $user->email,
                "user_password" => $this->random_password,
            ];
            
            Mail::to($user->email)->send(new UserAccount($context));

            $this->resetFields();
            
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
        $singleUser     = User::find($id);
        $this->hiddenId = $singleUser->id;
        $this->name     = $singleUser->name;
        $this->email    = $singleUser->email;
        $this->btnType  = 'Update';
    }

    // softDelete
    public function softDelete($id)
    {
        try {
            $data = User::find($id);
            if ($data != null) {
                $data->delete();
                $this->dispatchBrowserEvent('success-message',['message' => 'User Deleted Successfully']);
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
            $data = User::onlyTrashed()->find($id);
            if ($data != null) {
                $data->restore();
                $this->dispatchBrowserEvent('success-message',['message' => 'User Restored Successfully']);
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
        $this->reset('name', 'email', 'hiddenId', 'btnType');
    }
}
