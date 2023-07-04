<?php

namespace App\Http\Controllers\OperatorManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\ChangePasswordRequest;
use App\Http\Requests\Operator\StoreRequest;
use App\Http\Requests\Operator\UpdateRequest;
use App\Models\Operator;
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
    public function index()
    {
        return response()->json([
            'operators' => Operator::with('roles')->get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {

            DB::transaction(function () use($request) {

                $avatar = $request->file('avatar');

                $avatar_name = $request->username . '.' . $avatar->getClientOriginalExtension();

                $operatorData = [
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'email' => $request->email,
                ];

                if (isset($request->avatar)) {
                    $operatorData['avatar'] = $avatar->storeAs('public/avatars', $avatar_name);
                }

                $operator = Operator::create($operatorData);

                $operator->assignRole(Role::find($request->role_id));
            });

            return response()->json([
                'message' => 'created successfully'
            ], 200);

        } catch (\Throwable $th) {

            Log::error($th->getMessage());

            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Operator $operator)
    {
        return response()->json([
            'operator' => $operator->load('roles')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Operator $operator)
    {
        try {

            DB::transaction(function () use($request, $operator){

                if ($operator->avatar) {
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

        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operator $operator)
    {
        try {

            DB::transaction(function () use($operator) {

                if ($operator->avatar) {
                    Storage::delete($operator->avatar);
                }

                $operator->syncRoles([]);

                $operator->delete();
            });

            return response()->json([
                'message' => 'deleted successfully'
            ], 200);

        } catch (\Throwable $th) {
            Log::errorg($th->getMessage());

            throw $th;
        }
    }

    public function changePassword(ChangePasswordRequest $request, Operator $operator)
    {
        try {

            if (! Hash::check($request->current_password, $operator->password)) {
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

        } catch (\Throwable $th) {

            Log::error($th->getMessage());

            throw $th;
        }
    }

}
