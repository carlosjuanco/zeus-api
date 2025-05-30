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
        Route::get('getMonths', 'getMonths');
        Route::put('closeMonth/{month}', 'closeMonth');
        Route::put('openMonth/{month}', 'openMonth');
});
// Rutas con política general (para "Secretaria de distrito" y "Secretaria de iglesia")
Route::get('getYears', [MonthController::class, 'getYears'])
    ->middleware(['auth:sanctum', 'can:getYears,App\Models\Month']);

// Rutas con política para "Secretaria de iglesia"
Route::put('getAllTheMonthsThatHaveInformation/{year}', [MonthController::class, 'getAllTheMonthsThatHaveInformation'])
    ->middleware(['auth:sanctum', 'can:getAllTheMonthsThatHaveInformation,App\Models\Month']);

// Rutas con política general para "Secretaria de iglesia"
Route::controller(ChurcheController::class)
    ->middleware(['auth:sanctum', 'can:viewAny,App\Models\Churche'])
    ->group(function () {
        Route::get('getChurcheWithConcepts', 'getChurcheWithConcepts');
        Route::post('storeChurcheWithConcepts', 'storeChurcheWithConcepts');
        Route::put('getChurcheWithConceptsWithMonth/{month}', 'getChurcheWithConceptsWithMonth');
        Route::put('monthlyReportOfTheChurchSecretary/{month}', 'monthlyReportOfTheChurchSecretary');
});

// Ruta exclusiva para "Secretaria de distrito"
Route::get('getForEachChurchTheSumOfAllTheWeeksOfTheMonthOpened', [ChurcheController::class, 'getForEachChurchTheSumOfAllTheWeeksOfTheMonthOpened'])
    ->middleware(['auth:sanctum', 'can:getForEachChurchTheSumOfAllTheWeeksOfTheMonthOpened,App\Models\Churche']);

Route::controller(ConceptController::class)
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('getConcepts', 'getConcepts');
});