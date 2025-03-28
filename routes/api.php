<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MonthController;

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

Route::controller(MonthController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('getMonthOpen', 'getMonthOpen');
        Route::get('getYears', 'getYears');
});