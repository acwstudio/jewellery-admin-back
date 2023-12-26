<?php

declare(strict_types=1);

use App\Http\Controllers\Payments\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/payments')->group(function () {
    Route::post('/webhook/status', [PaymentController::class, 'webhookStatus']);
});
