<?php

declare(strict_types=1);

use App\Http\Controllers\Delivery\CarrierController;
use App\Http\Controllers\Delivery\CurrierDeliveryController;
use App\Http\Controllers\Delivery\PvzController;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/delivery')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/currier', [CurrierDeliveryController::class, 'create']);
    });

    Route::get('/pvz', [PvzController::class, 'get']);
    Route::get('/pvz/{id}', [PvzController::class, 'getById']);
    Route::get('/carrier', [CarrierController::class, 'get']);
    Route::get('/carrier/{id}', [CarrierController::class, 'getById']);

    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::post('/pvz', [PvzController::class, 'create']);
        Route::put('/pvz', [PvzController::class, 'update']);
        Route::delete('/pvz/{id}', [PvzController::class, 'delete']);
        Route::post('/carrier', [CarrierController::class, 'create']);
        Route::put('/carrier', [CarrierController::class, 'update']);
        Route::delete('/carrier/{id}', [CarrierController::class, 'delete']);
    });
});
