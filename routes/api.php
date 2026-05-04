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

Route::get('communities', [CommunityController::class, 'viewAny'])
    ->middleware(['auth:sanctum', 'can:viewAny,App\Models\Community'])
    ->name('communities.viewAny');

Route::post('communities/store', [CommunityController::class, 'store'])
    ->middleware(['auth:sanctum', 'can:create,App\Models\Community'])
    ->name('communities.store');

Route::put('communities/{community}', [CommunityController::class, 'update'])
    ->middleware(['auth:sanctum', 'can:update,community'])
    ->name('communities.update');
    
Route::delete('communities/{community}', [CommunityController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'can:delete,community'])
    ->name('communities.destroy');