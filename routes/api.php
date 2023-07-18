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

Route::controller(AuthController::class)->group(function() {
    Route::post('/user_register', 'userRegister');
    Route::post('/user_login', 'userLogin');
    Route::post('/user_logout', 'userLogout')->middleware('auth:user');

    Route::post('/operator_login', 'operatorLogin');
    Route::post('/operator_logout', 'operatorLogout')->middleware('auth:operator');

    Route::post('/admin_login', 'AdminLogin');
    Route::post('/admin_logout', 'AdminLogout')->middleware('auth:admin');
});

Route::middleware('role:admin')->controller(RoleController::class)->prefix('roles')->group(function() {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{role}', 'show')->where('role', '[0-9]+');
    Route::post('/{role}', 'update')->where('role', '[0-9]+');
    Route::delete('/{role}', 'destroy')->where('role', '[0-9]+');
});

Route::middleware('role:admin')->controller(PermissionController::class)->prefix('permissions')->group(function() {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{permission}', 'show')->where('permission', '[0-9]+');
    Route::post('/{permission}', 'update')->where('permission', '[0-9]+');
    Route::delete('/{permission}', 'destroy')->where('permission', '[0-9]+');
});

Route::prefix('movies')->controller(MovieController::class)->group(function() {
    Route::get('/', 'index');
    Route::post('/', 'store')->middleware('role:admin,movie_managing');
    Route::get('/{movie}', 'show')->where('movie', '[0-9]+');
    Route::post('/{movie}', 'update')->where('movie', '[0-9]+')->middleware('role:admin,movie_managing');
    Route::delete('/{movie}', 'destroy')->where('movie', '[0-9]+')->middleware('role:admin,movie_managing');
});

Route::middleware('role:admin,user_managing')->controller(UserController::class)->prefix('users')->group(function() {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{user}', 'show')->where('user', '[0-9]+');
    Route::post('/{user}', 'update')->where('user', '[0-9]+');
    Route::delete('/{user}', 'destroy')->where('user', '[0-9]+');
    Route::post('/changePassword/{user}', 'changePassword')->where('user', '[0-9]+');
});

Route::middleware('role:admin')->controller(OperatorController::class)->prefix('operators')->group(function() {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{operator}', 'show')->where('operator', '[0-9]+');
    Route::post('/{operator}', 'update')->where('operator', '[0-9]+');
    Route::delete('/{operator}', 'destroy')->where('operator', '[0-9]+');
    Route::post('/change_password/{operator}', 'changePassword')->where('operator', '[0-9]+');
});


Route::get('/profile_info', [ProfileController::class, 'info'])->middleware('auth:user');

Route::get('/operator_info', [OperatorProfileController::class, 'profile'])->middleware('auth:operator');
