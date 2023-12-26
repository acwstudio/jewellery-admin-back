<?php

declare(strict_types=1);

use App\Http\Controllers\Vacancies\DepartmentController;
use App\Http\Controllers\Vacancies\JobController;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('/v1/vacancies')->group(function () {
    Route::get('/department', [DepartmentController::class, 'index'])
        ->name('api.v1.vacancy.department.index');
    Route::get('/department/{id}', [DepartmentController::class, 'show'])
        ->name('api.v1.vacancy.department.show')
        ->whereNumber('id');

    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::post('/department', [DepartmentController::class, 'create'])
            ->name('api.v1.vacancy.department.store');
        Route::put('/department/{id}', [DepartmentController::class, 'update'])
            ->name('api.v1.vacancy.department.update')
            ->whereNumber('id');
        Route::delete('/department/{id}', [DepartmentController::class, 'destroy'])
            ->name('api.v1.vacancy.department.destroy')
            ->whereNumber('id');
    });


    Route::get('/vacancy', [JobController::class, 'index'])
        ->name('api.v1.vacancy.vacancy.index');
    Route::get('/vacancy/{id}', [JobController::class, 'show'])
        ->name('api.v1.vacancy.vacancy.show')
        ->whereNumber('id');
    Route::get('/vacancy/{slug}', [JobController::class, 'slug'])
        ->name('api.v1.vacancy.vacancy.slug');
    Route::post('/vacancy/apply', [JobController::class, 'applyVacancy'])
        ->name('api.v1.vacancy.vacancy.apply')
        ->whereNumber('id');

    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::post('/vacancy', [JobController::class, 'create'])
            ->name('api.v1.vacancy.vacancy.store');
        Route::put('/vacancy/{id}', [JobController::class, 'update'])
            ->name('api.v1.vacancy.vacancy.update')
            ->whereNumber('id');
        Route::delete('/vacancy/{id}', [JobController::class, 'destroy'])
            ->name('api.v1.vacancy.vacancy.destroy')
            ->whereNumber('id');
    });
});
