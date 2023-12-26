<?php

declare(strict_types=1);

use App\Http\Controllers\Users\OrderController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\WishlistController;
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
Route::middleware(['auth:sanctum'])->prefix('/v1/user')->group(function () {
    Route::get('/profile', [UserController::class, 'getProfile'])
        ->name('api.v1.users.profile.get');
    Route::put('/profile', [UserController::class, 'updateProfile'])
        ->name('api.v1.users.profile.update');

    Route::get('/wishlist', [WishlistController::class, 'getList']);
    Route::get('/wishlist/short', [WishlistController::class, 'short']);
    Route::post('/wishlist/{product_id}', [WishlistController::class, 'create'])
        ->whereNumber('product_id');
    Route::delete('/wishlist/{product_id}', [WishlistController::class, 'delete'])
        ->whereNumber('product_id');

    Route::get('/order', [OrderController::class, 'getList']);
    Route::get('/order/{id}', [OrderController::class, 'get'])
        ->whereNumber('id');
});
