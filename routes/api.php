<?php

declare(strict_types=1);

use App\Http\Controllers\Users\AuthController;
use App\Http\Controllers\Collections\CollectionController;
use App\Http\Controllers\Collections\CollectionProductController;
use App\Http\Controllers\Collections\FavoriteController;
use App\Http\Controllers\Collections\FileController as CollectionFileController;
use App\Http\Controllers\Collections\StoneController;
use App\Http\Controllers\DadataController;
use App\Http\Controllers\Blog\CategoryController as BlogCategoryController;
use App\Http\Controllers\Blog\PostController;
use App\Http\Controllers\OTP\OtpController;
use App\Http\Controllers\Rules\RuleController;
use App\Http\Controllers\Storage\FileController;
use App\Http\Controllers\Stores\StoreController;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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
Route::prefix('/v1/auth')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/oauth', [AuthController::class, 'oauth'])->name('oauth');
    });
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::withoutMiddleware(["auth"])->prefix('/v1/')->group(function () {
    Route::get('/get_suggestions_by_query', [DadataController::class, 'getSuggestionsByQuery']);
    Route::get('/get_address_by_coords', [DadataController::class, 'getAddressByCoords']);
});

Route::withoutMiddleware(["auth"])->prefix('/v1/')->group(function () {
    Route::get('/ping', function () {
        return response(['data' => 'pong']);
    });

    Route::get('/mib', function () {
        return response(['data' => Str::random(1024*1024)]);
    });

    Route::get('/shop/types', [StoreController::class, 'getStoreTypes'])->name('api.v1.stores.shop.types');

    Route::resource('/shop', StoreController::class)->names([
        'index' => 'api.v1.stores.shop.index',
        'show' => 'api.v1.stores.shop.show',
        'store' => 'api.v1.stores.shop.store',
        'update' => 'api.v1.stores.shop.update',
        'destroy' => 'api.v1.stores.shop.destroy',
    ]);

    Route::get('/rule', [RuleController::class, 'index'])
        ->name('api.v1.rules.rule.index');
    Route::get('/rule/{id}', [RuleController::class, 'show'])
        ->name('api.v1.rules.rule.show')
        ->whereNumber('id');
    Route::get('/rule/{slug}', [RuleController::class, 'slug'])
        ->name('api.v1.rules.rule.slug');

    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::post('/rule', [RuleController::class, 'create'])
            ->name('api.v1.rules.rule.store');
        Route::put('/rule/{id}', [RuleController::class, 'update'])
            ->name('api.v1.rules.rule.update')
            ->whereNumber('id');
        Route::delete('/rule/{id}', [RuleController::class, 'destroy'])
            ->name('api.v1.rules.rule.destroy')
            ->whereNumber('id');
    });
});

/**
 * Blog
 */
Route::prefix('/v1/blog')->group(function () {
    Route::get('/category/{slug}', [BlogCategoryController::class, 'get']);
    Route::get('/category', [BlogCategoryController::class, 'getList']);

    Route::get('/post/{slug}', [PostController::class, 'get']);
    Route::get('/post', [PostController::class, 'getList']);

    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        /** Category */
        Route::post('/category', [BlogCategoryController::class, 'create']);
        Route::put('/category', [BlogCategoryController::class, 'update']);
        Route::delete('/category/{id}', [BlogCategoryController::class, 'delete']);

        /** Post */
        Route::post('/post', [PostController::class, 'create']);
        Route::put('/post', [PostController::class, 'update']);
        Route::delete('/post/{id}', [PostController::class, 'delete']);
    });
});

/** Storage */
Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->prefix('/v1/storage')->group(function () {
    Route::get('/file/{id}', [FileController::class, 'get']);
    Route::post('/file/upload', [FileController::class, 'upload']);
    Route::delete('/file/{id}', [FileController::class, 'delete']);
});

/** Collections */
Route::prefix('/v1/collections')->group(function () {
    Route::get('/collection', [CollectionController::class, 'getList']);
    Route::get('/collection/{slug}', [CollectionController::class, 'get']);

    Route::get('/favorite', [FavoriteController::class, 'getList']);
    Route::get('/favorite/{slug}', [FavoriteController::class, 'get']);

    Route::get('/stone', [StoneController::class, 'getList']);

    Route::get('/collection/{id}/product', [CollectionProductController::class, 'getList'])
        ->whereNumber('id');

    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::post('/collection', [CollectionController::class, 'create']);
        Route::put('/collection/{id}', [CollectionController::class, 'update'])
            ->whereNumber('id');
        Route::delete('/collection/{id}', [CollectionController::class, 'delete'])
            ->whereNumber('id');

        Route::post('/favorite', [FavoriteController::class, 'create']);
        Route::put('/favorite/{id}', [FavoriteController::class, 'update'])
            ->whereNumber('id');
        Route::delete('/favorite/{id}', [FavoriteController::class, 'delete'])
            ->whereNumber('id');

        Route::get('/file', [CollectionFileController::class, 'getList']);
        Route::post('/file', [CollectionFileController::class, 'create']);
        Route::delete('/file/{id}', [CollectionFileController::class, 'delete'])
            ->whereNumber('id');

        Route::post('/stone', [StoneController::class, 'create']);
        Route::delete('/stone/{id}', [StoneController::class, 'delete'])
            ->whereNumber('id');
    });
});

/** OTP */
Route::prefix('/v1/otp')->group(function () {
    Route::post('/send', [OtpController::class, 'send']);
});
