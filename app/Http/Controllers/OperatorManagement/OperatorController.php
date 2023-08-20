<?php

namespace App\Http\Controllers\OperatorManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\ChangePasswordRequest;
use App\Http\Requests\Operator\StoreRequest;
use App\Http\Requests\Operator\UpdateRequest;
use App\Http\Resources\OperatorResource;
use App\Models\Operator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(OperatorResource::collection(Operator::all()));
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
                $operatorData = [
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'email' => $request->email,
                ];

                if (isset($request->avatar))
                {
                    $avatar = $request->file('avatar');

                    $avatar_name = $request->username . '.' . $avatar->getClientOriginalExtension();

                    $operatorData['avatar'] = $avatar->storeAs('public/avatars', $avatar_name);
                }

                $operator = Operator::create($operatorData);

                $operator->assignRole(Role::find($request->role_id));
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
    public function show(Operator $operator): JsonResponse
    {
        return response()->json([
            'operator' => $operator->load('roles')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Operator $operator): JsonResponse
    {
        try
        {
            DB::transaction(function () use($request, $operator)
            {
                if ($operator->avatar)
                {
                    Storage::delete($operator->avatar);
                }

                $avatar = $request->file('avatar');

                $avatar_name = $request->username . '.' . $avatar->getClientOriginalExtension();

                $operator->update([
                    'username' => $request->username,
                    'avatar' => $avatar->storeAs('public/avatars', $avatar_name),
                    'email' => $request->email,
                ]);

                $operator->syncRoles($request->role_id);
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
    public function destroy(Operator $operator): JsonResponse
    {
        try
        {
            DB::transaction(function () use($operator)
            {
                if ($operator->avatar)
                {
                    Storage::delete($operator->avatar);
                }

                $operator->syncRoles([]);

                $operator->delete();
            });

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

    /**
     * @throws \Throwable
     */
    public function changePassword(ChangePasswordRequest $request, Operator $operator): JsonResponse
    {
        try
        {
            if (! Hash::check($request->current_password, $operator->password))
            {
                return response()->json([
                    'message' => 'wrong current password'
                ], 422);
            }

            $operator->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'message' => 'your password has been changed'
            ], 200);
        }
        catch (\Throwable $th)
        {
            Log::error($th->getMessage());

            throw $th;
        }
    }
}
