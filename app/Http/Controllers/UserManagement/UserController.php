<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
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
    public function index()
    {
        return response()->json([
            'users' => User::with('roles')->get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {

            $status = DB::transaction(function () use($request) {

                $avatar = $request->file('avatar');
                $avatar_name = $request->username . '.' . $avatar->getClientOriginalExtension();

                $user = User::create([
                    "username" => $request->username,
                    "password" => Hash::make($request->password),
                    'email' => $request->email,
                    'avatar' => $avatar->storeAs('public/avatars', $avatar_name),
                ]);
                
                $user->assignRole(Role::find($request->role_id));

                return true;
                 
            });

        } catch (\Throwable $th) {

            Log::error($th->getMessage());
            
            throw $th;
        }  
        
        if ($status) {
            return response()->json([
                'message' => 'created successfully'
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'user' => $user->load('roles')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        try {

            $status = DB::transaction(function () use($request, $user){

                if ($user->avatar) {
                    Storage::delete($user->avatar);
                }
                
                $avatar = $request->file('avatar');
                $avatar_name = $request->username . '.' . $avatar->getClientOriginalExtension();

                $user->update([
                    "username" => $request->username,
                    'email' => $request->email,
                    'avatar' => $avatar->storeAs('public/avatars', $avatar_name),
                ]);

                $user->syncRoles($request->role_id);

                return true;
            });

        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            
            throw $th;
        }
        
        if ($status) {
            return response()->json([
                'message' => 'updated successfully'
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->avatar) {
            Storage::delete($user->avatar);
        }

        $user->syncRoles([]);

        $user->delete();

        return response()->json([
            'message' => 'deleted successfully'
        ], 200);
    }
}
