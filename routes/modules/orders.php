<?php

declare(strict_types=1);

use App\Http\Controllers\Orders\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/orders')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/order/{id}', [OrderController::class, 'get']);
        Route::post('/order', [OrderController::class, 'create']);
    });
});
