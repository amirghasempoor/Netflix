<?php

namespace App\Http\Controllers\PermissionManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StoreRequest;
use App\Http\Requests\Permission\UpdateRequest;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'permissions' => Permission::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        Permission::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'created successfully',
        ], 200);

        try {

            Permission::create([
                'name' => $request->name
            ]);

            return response()->json([
                'message' => 'created successfully',
            ], 200);

        } catch (\Throwable $th) {

            Log::error($th->getMessage());

            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return response()->json([
            'permission' => $permission
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Permission $permission)
    {
        $permission->update($request->all());

        return response()->json([
            'message' => 'updated successfully'
        ], 200);

        try {

            $permission->update($request->all());

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
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'messsage' => 'deleted successfully'
        ], 200);

        try {

            $permission->delete();

            return response()->json([
                'messsage' => 'deleted successfully'
            ], 200);

        } catch (\Throwable $th) {

            Log::error($th->getMessage());

            throw $th;
        }
    }
}
