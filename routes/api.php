<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommunityController;

Route::post('login', [AuthController::class, 'login']);

Route::controller(AuthController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('check', 'check');
        Route::post('logout', 'logout');
});

Route::controller(UserController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('users', 'store');
});

Route::get('index', [CommunityController::class, 'index'])
    ->middleware(['auth:sanctum', 'can:index,App\Models\Community']);

Route::post('store', [CommunityController::class, 'store'])
    ->middleware(['auth:sanctum', 'can:create,App\Models\Community']);

Route::put('update/{community}', [CommunityController::class, 'update'])
    ->middleware(['auth:sanctum', 'can:update,community']);
    
Route::delete('destroy/{community}', [CommunityController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'can:delete,community']);