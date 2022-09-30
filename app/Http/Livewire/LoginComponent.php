<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class LoginComponent extends Component
{
    public $error;

    public $fullname;
    public $email;
    public $password;
    public $validationRules = false;

    protected function rules()
    {
        if ($this->validationRules) {
            return [
                'fullname' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ];
        } else {
            return [
                'email' => 'required|email',
                'password' => 'required|min:8',
            ];
        }
    }

    public function render()
    {
        return view('livewire.login-component');
    }

    // register
    public function registerSubmit()
    {
        $this->validationRules = true;
        $this->validate(); // validate User Registeration form

        DB::beginTransaction();

        try {

            $user = new User(); // create user

            $user->name  = $this->fullname;
            $user->email  = $this->email;
            $user->password  = Hash::make($this->password);
            $user->save();

            DB::commit();

            $this->dispatchBrowserEvent('success-message', ['message' => 'Your registration has been completed.']);
            $this->dispatchBrowserEvent('close-auth-modal');

            $this->reset('fullname', 'email', 'password');
        } catch (\Throwable $th) {
            DB::rollback();
            // $this->error = $th->getMessage();
            $this->error = 'Ops! looks like we had some problem';
            $this->dispatchBrowserEvent('error-message', ['message' => $this->error]);
        }
    }

    // login
    public function loginSubmit()
    {
        $this->validationRules = false;
        $this->validate(); // validate User Registeration form

        $user = User::where('email', '=', $this->email)->first()->is_admin;

        $userdata = array(
            'email' => $this->email,
            'password' => $this->password
        );
        // attempt to do the login
        if (Auth::attempt($userdata)) {
            if($user) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('client.dashboard');
            }
                
        } else {
            $this->dispatchBrowserEvent('error-message', ['message' => 'User Email or Password do not match']);
            $this->reset('password');
        }
    }
}
