<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieManagement\MovieController;
use App\Http\Controllers\OperatorManagement\OperatorController;
use App\Http\Controllers\PermissionManagement\PermissionController;
use App\Http\Controllers\Profile\OperatorProfileController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\RoleManagement\RoleController;
use App\Http\Controllers\UserManagement\UserController;
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
Route::post('/user_logout', [AuthController::class, 'userLogout'])->middleware('auth:user');

Route::post('/operator_register', [AuthController::class, 'operatorRegister']);
Route::post('/operator_login', [AuthController::class, 'operatorLogin']);
Route::post('/operator_logout', [AuthController::class, 'operatorLogout'])->middleware('auth:operator');

Route::post('/admin_login', [AuthController::class, 'AdminLogin']);
Route::post('/admin_logout', [AuthController::class, 'AdminLogout'])->middleware('auth:admin');

Route::middleware('auth:admin')->prefix('roles')->group(function() {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::get('/{role}', [RoleController::class, 'show'])->where('role', '[0-9]+');
    Route::put('/{role}', [RoleController::class, 'update'])->where('role', '[0-9]+');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->where('role', '[0-9]+');
});

Route::middleware('auth:admin')->prefix('permissions')->group(function() {
    Route::get('/', [PermissionController::class, 'index']);
    Route::post('/', [PermissionController::class, 'store']);
    Route::get('/{permission}', [PermissionController::class, 'show'])->where('permission', '[0-9]+');
    Route::put('/{permission}', [PermissionController::class, 'update'])->where('permission', '[0-9]+');
    Route::delete('/{permission}', [PermissionController::class, 'destroy'])->where('permission', '[0-9]+');
});

Route::prefix('movies')->group(function() {
    Route::get('/', [MovieController::class, 'index']);//->middleware('auth:sanctum');
    Route::post('/', [MovieController::class, 'store'])->middleware(['auth:operator', 'auth:admin']);
    Route::get('/{movie}', [MovieController::class, 'show'])->where('movie', '[0-9]+');//->middleware('auth:sanctum');
    Route::put('/{movie}', [MovieController::class, 'update'])->where('movie', '[0-9]+')->middleware(['auth:operator', 'auth:admin']);
    Route::delete('/{movie}', [MovieController::class, 'destroy'])->where('movie', '[0-9]+')->middleware(['auth:operator', 'auth:admin']);
});

Route::middleware(['auth:operator', 'auth:admin'])->prefix('users')->group(function() {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{user}', [UserController::class, 'show'])->where('user', '[0-9]+');
    Route::put('/{user}', [UserController::class, 'update'])->where('user', '[0-9]+');
    Route::delete('/{user}', [UserController::class, 'destroy'])->where('user', '[0-9]+');
    Route::post('/changePassword/{user}', [UserController::class, 'changePassword'])->where('user', '[0-9]+');
});

Route::middleware('auth:admin')->prefix('operator')->group(function() {
    Route::get('/', [OperatorController::class, 'index']);
    Route::post('/', [OperatorController::class, 'store']);
    Route::get('/{operator}', [OperatorController::class, 'show'])->where('operator', '[0-9]+');
    Route::post('/{operator}', [OperatorController::class, 'update'])->where('operator', '[0-9]+');
    Route::delete('/{operator}', [OperatorController::class, 'destroy'])->where('operator', '[0-9]+');
    Route::post('change_password/{operator}', [OperatorController::class, 'changePassword'])->where('operator', '[0-9]+');
});


Route::get('/profile_info', [ProfileController::class, 'info'])->middleware('auth:user');

Route::get('/operator_info', [OperatorProfileController::class, 'profile'])->middleware('auth:operator');