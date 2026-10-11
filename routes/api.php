<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\TeacherController;

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

// viewAny - Listar comunidades con paginación y búsqueda opcional
Route::get('communities/{paginate}/{search?}', [CommunityController::class, 'viewAny'])
    ->middleware(['auth:sanctum', 'can:viewAny,App\Models\Community'])
    ->name('communities.viewAny');

// store - Crear nueva comunidad
Route::post('communities/store', [CommunityController::class, 'store'])
    ->middleware(['auth:sanctum', 'can:create,App\Models\Community'])
    ->name('communities.store');

// update - Actualizar comunidad existente
Route::put('communities/{community}', [CommunityController::class, 'update'])
    ->middleware(['auth:sanctum', 'can:update,community'])
    ->name('communities.update');

// destroy - Eliminar comunidad
Route::delete('communities/{community}', [CommunityController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'can:delete,community'])
    ->name('communities.destroy');

// getAllTheCommunities - Listar todas las comunidades para llenar elementos select
// Por el momento puedo ocupar el mismo permiso de viewAny.
Route::get('communities', [CommunityController::class, 'getAllTheCommunities'])
    ->middleware(['auth:sanctum', 'can:viewAny,App\Models\Community'])
    ->name('communities.getAllTheCommunities');

// viewAny - Listar escuelas con paginación y búsqueda opcional
Route::get('schools/{paginate}/{search?}', [SchoolController::class, 'viewAny'])
    ->middleware(['auth:sanctum', 'can:viewAny,App\Models\School'])
    ->name('schools.viewAny');

// store - Crear nueva escuela
Route::post('schools/store', [SchoolController::class, 'store'])
    ->middleware(['auth:sanctum', 'can:create,App\Models\School'])
    ->name('schools.store');

// update - Actualizar escuela existente
Route::put('schools/{school}', [SchoolController::class, 'update'])
    ->middleware(['auth:sanctum', 'can:update,school'])
    ->name('schools.update');
    
// destroy - Eliminar escuela
Route::delete('schools/{school}', [SchoolController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'can:delete,school'])
    ->name('schools.destroy');

// getAllTheSchools - Listar todas las escuelas para llenar elementos select
// Por el momento puedo ocupar el mismo permiso de viewAny.
Route::get('schools', [SchoolController::class, 'getAllTheSchools'])
    ->middleware(['auth:sanctum', 'can:viewAny,App\Models\Community'])
    ->name('schools.getAllTheSchools');

// viewAny - Listar profesores con paginación y búsqueda opcional
Route::get('teachers/{paginate}/{search?}', [TeacherController::class, 'viewAny'])
    ->middleware(['auth:sanctum', 'can:viewAny,App\Models\Teacher'])
    ->name('teachers.viewAny');

// store - Crear nuevo profesor
Route::post('teachers/store', [TeacherController::class, 'store'])
    ->middleware(['auth:sanctum', 'can:create,App\Models\Teacher'])
    ->name('teachers.store');

// update - Actualizar profesor existente
Route::put('teachers/{teacher}', [TeacherController::class, 'update'])
    ->middleware(['auth:sanctum', 'can:update,teacher'])
    ->name('teachers.update');
    
// destroy - Eliminar profesor
Route::delete('teachers/{teacher}', [TeacherController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'can:delete,teacher'])
    ->name('teachers.destroy');