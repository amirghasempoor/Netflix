<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function userRegister(UserRegisterRequest $request)
    {
        $avatar = $request->file('avatar');
        $avatar_name = $request->username . '.' . $avatar->getClientOriginalExtension();

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'avatar' => $avatar->storeAs('public/avatars', $avatar_name),
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
        if (Storage::exists(auth()->user()->avatar)) {
            Storage::delete(auth()->user()->avatar);
        }

        auth()->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'logged out successfully',
        ], 200);
    }
}
