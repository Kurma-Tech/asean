<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where(['email' => $request->email])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response(["message" => "The provided credentials are incorrect."], 422);
        }

        return response(["access_token" => $user->createToken($user->name)->plainTextToken], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' =>  'required_if:type,null|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where("email", $request->email);
        if ($user->exists()) return response(["message" => "User with this email has already been created."], 422);

        $request->merge([
            'password' => Hash::make($request->password),
        ]);

        $user = User::create($request->all());
        return response(["access_token" => $user->createToken($user->name)->plainTextToken], 200);
    }

    public function getUser(Request $request)
    {
        return response($request->user(), 200);
    }
}
