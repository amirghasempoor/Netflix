<?php

namespace App\Http\Controllers\RoleManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(RoleResource::collection(Role::all()));
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try
        {
            $role = Role::create([
                'name' => $request->name
            ]);

            $role->givePermissionTo($request->permission_id);

            return response()->json([
                'message' => 'created successfully',
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
    public function show(Role $role): JsonResponse
    {
        return response()->json([
            'role' => $role->load('permissions'),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(UpdateRequest $request, Role $role): JsonResponse
    {
        try
        {
            $role->update([
                'name' => $request->name
            ]);

            $role->syncPermissions($request->permission_id);

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
    public function destroy(Role $role): JsonResponse
    {
        try
        {
            $role->syncPermissions([]);

            $role->delete();

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
}
