<?php

declare(strict_types=1);

use App\Http\Controllers\Checkout\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('/v1/checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'get']);
    Route::get('/summary', [CheckoutController::class, 'summary']);
});

Route::prefix('/v1/checkout')->group(function () {
    Route::get('/calculate', [CheckoutController::class, 'calculate']);
});
