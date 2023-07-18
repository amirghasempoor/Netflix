<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\OperatorLoginRequest;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\Operator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function userRegister(UserRegisterRequest $request)
    {
        $userData = [
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email' => $request->email,
        ];

        if (isset($request->avatar))
        {
            $avatar = $request->file('avatar');

            $avatar_name = $request->username . '.' . $avatar->getClientOriginalExtension();

            $userData['avatar'] = $avatar->storeAs('public/avatars', $avatar_name);
        }

        $user = User::create($userData);

        $token = $user->createToken('USER_TOKEN')->plainTextToken;

        return response()->json([
            'message' => 'registered successfully',
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

    public function deleteAccount(User $user)
    {
        if (Storage::exists(auth()->user()->avatar)) {
            Storage::delete(auth()->user()->avatar);
        }

        $user->delete();

        return response()->json([
            'message' => 'your account deleted successfully'
        ], 200);
    }

    public function operatorLogin(OperatorLoginRequest $request)
    {
        $operator = Operator::where('username', $request->username)->first();

        if (! $operator || ! Hash::check($request->password, $operator->password))
        {
            return response()->json([
                'message' => 'incorrect user name or password'
            ], 422);
        }

        $token = $operator->createToken('OPERATOR_TOKEN')->plainTextToken;

        return response()->json([
            'message' => 'logged in successfully',
            'token' => $token
        ], 200);
    }

    public function operatorLogout()
    {
        auth('operator')->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'logged out successfully',
        ], 200);
    }
}
