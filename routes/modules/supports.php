<?php

declare(strict_types=1);

use App\Http\Controllers\Support\SupportMailController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/supports')->group(function () {
    Route::post('/no-size', [SupportMailController::class, 'sendMailNoSize']);
});
