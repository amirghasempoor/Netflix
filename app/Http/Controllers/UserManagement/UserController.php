<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(UserResource::collection(User::all()));
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try
        {
            DB::transaction(function () use($request)
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

                User::create($userData);
            });

            return response()->json([
                'message' => 'created successfully'
            ], 200);
        }
        catch (\Throwable $th)
        {
            Log::error($th->getMessage());

            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'user' => $user->load('roles')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        try
        {
            DB::transaction(function () use($request, $user)
            {
                $userData = [
                    'username' => $request->username,
                    'email' => $request->email,
                ];

                if ($user->avatar)
                {
                    if ($request->avatar) {
                        Storage::delete($user->avatar);
                    }

                    $avatar = $request->file('avatar');

                    $avatar_name = $request->username . '.' . $avatar->getClientOriginalExtension();

                    $userData['avatar'] = $avatar->storeAs('public/avatars', $avatar_name);
                }

                $user->update($userData);
            });

            return response()->json([
                'message' => 'updated successfully'
            ], 200);
        }
        catch (\Throwable $th)
        {
            Log::error($th->getMessage());

            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(User $user): JsonResponse
    {
        try
        {
            if ($user->avatar)
            {
                Storage::delete($user->avatar);
            }

            $user->delete();

            return response()->json([
                'message' => 'deleted successfully'
            ], 200);
        }
        catch (\Throwable $th)
        {
            Log::error($th->getMessage());

            throw $th;
        }
    }

    public function changePassword(ChangePasswordRequest $request, User $user): JsonResponse
    {
        try
        {
            if (! Hash::check($request->current_password, $user->password))
            {
                return response()->json([
                    'message' => 'wrong current password'
                ], 422);
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'message' => 'the password has been changed'
            ], 200);
        }
        catch (\Throwable $th)
        {
            Log::error($th->getMessage());

            return response()->json([
                'message' => 'An error occurred during the process'
            ], 500);
        }
    }
}
