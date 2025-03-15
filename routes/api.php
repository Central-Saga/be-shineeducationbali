<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ClassTypeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\MeetingFrequencyController;

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
    // Class Types
    Route::middleware('permission:mengelola class types')->group(function () {
        Route::apiResource('class-types', ClassTypeController::class);
        Route::patch('class-types/{id}/status', [ClassTypeController::class, 'updateStatus']);
    });
    // Education Levels
    Route::middleware('permission:mengelola education levels')->group(function () {
        Route::apiResource('education-levels', EducationLevelController::class);
        Route::patch('education-levels/{id}/status', [EducationLevelController::class, 'updateStatus']);
    });
    // Meeting Frequencies
    Route::middleware('permission:mengelola meeting frequencies')->group(function () {
        Route::apiResource('meeting-frequencies', MeetingFrequencyController::class);
        Route::patch('meeting-frequencies/{id}/status', [MeetingFrequencyController::class, 'updateStatus']);
    });
    // Subjects
    Route::middleware('permission:mengelola subjects')->group(function () {
        Route::apiResource('subjects', SubjectController::class);
        Route::patch('subjects/{id}/status', [SubjectController::class, 'updateStatus']);
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
