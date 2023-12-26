<?php

declare(strict_types=1);

use App\Http\Controllers\Promotions\PromocodeController;
use App\Http\Controllers\Promotions\SaleProductController;
use App\Http\Controllers\Promotions\SaleController;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/promotions')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/promocode/apply', [PromocodeController::class, 'apply']);
    });
    Route::post('/promocode/cancel', [PromocodeController::class, 'cancel']);

    Route::get('/sale', [SaleController::class, 'getList']);
    Route::get('/sale/{slug}', [SaleController::class, 'get']);

    Route::get('/sale_product', [SaleProductController::class, 'getList']);
});
