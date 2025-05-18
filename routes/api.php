<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\ChurcheController;
use App\Http\Controllers\ConceptController;

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
    ->middleware(['auth:sanctum', 'can:viewAny,App\Models\Month'])
    ->group(function () {
        Route::get('getYears', 'getYears');
        Route::get('getMonths', 'getMonths');
        Route::put('closeMonth/{month}', 'closeMonth');
        Route::put('openMonth/{month}', 'openMonth');
});

Route::controller(ChurcheController::class)
    ->middleware(['auth:sanctum', 'can:viewAny,App\Models\Churche'])
    ->group(function () {
        Route::get('getChurches', 'getChurches');
        Route::get('getChurcheWithConcepts', 'getChurcheWithConcepts');
        Route::post('storeChurcheWithConcepts', 'storeChurcheWithConcepts');
});

Route::controller(ConceptController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('getConcepts', 'getConcepts');
});