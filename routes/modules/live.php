<?php

declare(strict_types=1);

use App\Http\Controllers\Live\BroadcastController;
use App\Http\Controllers\Live\LiveProductController;
use App\Http\Controllers\Live\SettingController;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/live')->group(function () {
    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::get('/setting', [SettingController::class, 'getList']);
        Route::post('/setting', [SettingController::class, 'createOrUpdate']);
    });

    Route::get('/product', [LiveProductController::class, 'getList']);
    Route::get('/product/popular', [LiveProductController::class, 'getListByPopular']);
    Route::get('/product/recently', [LiveProductController::class, 'getListByRecently']);
    Route::get('/product/on_live', [LiveProductController::class, 'getListByOnLive']);
    Route::get('/broadcast', [BroadcastController::class, 'get']);
});
