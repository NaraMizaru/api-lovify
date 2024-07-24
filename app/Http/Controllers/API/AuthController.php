<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required',
            'username' => 'required|min:3|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone_number' =>  'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'error' => $validator->errors()
            ], 400);
        }

        $user = new User();
        $user->fullname = $request->fullname;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->phone_number = $request->phone_number;
        $user->save();

        if ($user->save()) {
            $token = $user->createToken('api_token')->plainTextToken;
            return response()->json([
                'message' => 'Register success',
                'token' => $token,
                'user' => $user
            ], 201);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|min:3',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'invalid fields',
                'error' => $validator->errors()
            ], 400);
        }

        $auth = Auth::attempt($request->only('username', 'password'));
        if ($auth) {
            $user = Auth::user();
            $token = $request->user()->createToken('api_token')->plainTextToken;
            return response()->json([
                'message' => 'Login success',
                'token' => $token,
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Username or password incorrect'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $check = Auth::check();
        if ($check) {
            $request->user()->Tokens()->delete();
            return response()->json([
                'message' => 'Logout success'
            ], 200);
        }
    }
}
