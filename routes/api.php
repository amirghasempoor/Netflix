<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionManagement\PermissionController;
use App\Http\Controllers\RoleManagement\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/user_register', [AuthController::class, 'userRegister']);
Route::post('/user_login', [AuthController::class, 'userLogin']);
Route::post('/user_logout', [AuthController::class, 'userLogout'])->middleware('auth:sanctum');

Route::prefix('roles')->group(function() {
    Route::get('/', [RoleController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/', [RoleController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/{role}', [RoleController::class, 'show'])->where('role', '[0-9]+')->middleware('auth:sanctum');
    Route::put('/{role}', [RoleController::class, 'update'])->where('role', '[0-9]+')->middleware('auth:sanctum');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->where('role', '[0-9]+')->middleware('auth:sanctum');
});

Route::prefix('permissions')->group(function() {
    Route::get('/', [PermissionController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/', [PermissionController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/{permission}', [PermissionController::class, 'show'])->where('permission', '[0-9]+')->middleware('auth:sanctum');
    Route::put('/{permission}', [PermissionController::class, 'update'])->where('permission', '[0-9]+')->middleware('auth:sanctum');
    Route::delete('/{permission}', [PermissionController::class, 'destroy'])->where('permission', '[0-9]+')->middleware('auth:sanctum');
});

Route::prefix('movies')->group(function() {
    Route::get('/', [MovieController::class, 'index']);//->middleware('auth:sanctum');
    Route::post('/', [MovieController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/{movie}', [MovieController::class, 'show'])->where('movie', '[0-9]+');//->middleware('auth:sanctum');
    Route::put('/{movie}', [MovieController::class, 'update'])->where('movie', '[0-9]+')->middleware('auth:sanctum');
    Route::delete('/{movie}', [MovieController::class, 'destroy'])->where('movie', '[0-9]+')->middleware('auth:sanctum');
});