<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth:sanctum', 'check.user.status'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Permissions
    Route::middleware('permission:mengelola permission')->group(function () {
        Route::apiResource('permissions', PermissionController::class);
        Route::patch('permissions/{id}/status', [PermissionController::class, 'updateStatus']);
    });
    // Roles
    Route::middleware('permission:mengelola role')->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::patch('roles/{id}/status', [RoleController::class, 'updateStatus']);
    });
    // Users
    Route::middleware('permission:mengelola user')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::patch('users/{id}/status', [UserController::class, 'updateStatus']);
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
