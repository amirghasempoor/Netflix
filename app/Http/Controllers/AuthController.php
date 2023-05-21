<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function userRegister(UserRegisterRequest $request)
    {
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);
        
        $token = $user->createToken('USER_TOKEN')->plainTextToken;

        return response()->json([
            'message' => 'registered successfully',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function userLogin(UserLoginRequest $request)
    {
        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password))
        {
            return response()->json([
                'message' => 'incorrect user name or password'
            ], 422);
        }

        $token = $user->createToken('USER_TOKEN')->plainTextToken;

        return response()->json([
            'message' => 'logged in successfully',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function userLogout()
    {
        auth()->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'logged out successfully',
        ], 200);
    }
}
