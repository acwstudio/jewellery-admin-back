<?php

declare(strict_types=1);

use App\Http\Controllers\ShopCart\ShopCartController;
use App\Http\Controllers\ShopCart\ShopCartItemController;
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
Route::prefix('/v1/shop_cart')->group(function () {
    Route::get('/', [ShopCartController::class, 'get']);
    Route::delete('/', [ShopCartController::class, 'delete']);
    Route::put('/item', [ShopCartItemController::class, 'add']);
    Route::delete('/item', [ShopCartItemController::class, 'delete']);
    Route::get('/short', [ShopCartController::class, 'short']);
});
