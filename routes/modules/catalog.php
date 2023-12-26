<?php

declare(strict_types=1);

use App\Http\Controllers\Catalog\BrandController;
use App\Http\Controllers\Catalog\BreadcrumbController;
use App\Http\Controllers\Catalog\CatalogFilterController;
use App\Http\Controllers\Catalog\CategoryController;
use App\Http\Controllers\Catalog\CategoryListController;
use App\Http\Controllers\Catalog\FeatureController;
use App\Http\Controllers\Catalog\ProductController;
use App\Http\Controllers\Catalog\ProductFeatureController;
use App\Http\Controllers\Catalog\ProductOfferController;
use App\Http\Controllers\Catalog\ProductOfferPriceController;
use App\Http\Controllers\Catalog\ProductOfferStockController;
use App\Http\Controllers\Catalog\ProductOfferReservationController;
use App\Http\Controllers\Catalog\PreviewImageController;
use App\Http\Controllers\Catalog\SeoController;
use App\Packages\Enums\Users\RoleEnum;
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
Route::prefix('/v1/catalog')->group(function () {
    Route::resource('/brand', BrandController::class)->names([
        'index' => 'api.v1.catalog.brand.index',
        'show' => 'api.v1.catalog.brand.show',
        'store' => 'api.v1.catalog.brand.store',
        'update' => 'api.v1.catalog.brand.update',
        'destroy' => 'api.v1.catalog.brand.destroy',
    ]);

    Route::get('/category', [CategoryController::class, 'getCategories']);
    Route::get('/category/{id}', [CategoryController::class, 'getCategory'])->whereNumber('id');
    Route::get('/category/{slug}', [CategoryController::class, 'getCategoryBySlug'])
        ->name('api.v1.catalog.category.slug.get');

    Route::get('/category_list', [CategoryListController::class, 'getCategoryList']);
    Route::get('/category_list/{id}', [CategoryListController::class, 'getCategoryListItem'])
        ->whereNumber('id');
    Route::get('/category_list/{slug}', [CategoryListController::class, 'getCategoryListItemBySlug'])
        ->name('api.v1.catalog.category.list.slug.get');

    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::post('/category', [CategoryController::class, 'createCategory']);
        Route::put('/category/{id}', [CategoryController::class, 'updateCategory'])
            ->whereNumber('id');
        Route::delete('/category/{id}', [CategoryController::class, 'deleteCategory'])
            ->whereNumber('id');
        Route::post('/category/{id}/slug', [CategoryController::class, 'createCategorySlugAlias'])
            ->whereNumber('id')
            ->name('api.v1.catalog.category.slug.create');
    });

    Route::get('/breadcrumbs/{id}', [BreadcrumbController::class, 'getBreadcrumbs'])
        ->whereNumber('id')
        ->name('api.v1.catalog.category.breadcrumbs.get.id');

    Route::get('/breadcrumbs/{slug}', [BreadcrumbController::class, 'getBreadcrumbsBySlug'])
        ->name('api.v1.catalog.category.breadcrumbs.get.slug');

    Route::get('/product', [ProductController::class, 'getList']);
    Route::get('/product/{slug}', [ProductController::class, 'get']);
    Route::get('/product_item_extended/{id}', [ProductController::class, 'getItemExtended']);
    Route::post('/product-by-seo', [ProductController::class, 'getListBySeo']);

    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::post('/product', [ProductController::class, 'create']);
        Route::put('/product/{id}', [ProductController::class, 'update'])->whereNumber('id');
        Route::delete('/product/{id}', [ProductController::class, 'delete'])->whereNumber('id');
    });

    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::get('/preview_image', [PreviewImageController::class, 'getList']);
        Route::post('/preview_image', [PreviewImageController::class, 'upload']);
        Route::delete('/preview_image/{id}', [PreviewImageController::class, 'delete'])
            ->whereNumber('id');
    });

    Route::get('/trade_offer/{id}', [ProductOfferController::class, 'get'])->whereNumber('id');

    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::post('/trade_offer', [ProductOfferController::class, 'create']);
        Route::delete('/trade_offer/{id}', [ProductOfferController::class, 'delete'])->whereNumber('id');

        Route::post('/trade_offer/{id}/price', [ProductOfferPriceController::class, 'create'])
            ->whereNumber('id');
        Route::put('/trade_offer/{id}/price/{type}', [ProductOfferPriceController::class, 'updateIsActive'])
            ->whereNumber('id')->whereAlpha('type');

        Route::post('/trade_offer/{id}/stock', [ProductOfferStockController::class, 'create'])
            ->whereNumber('id');

        Route::post('/trade_offer/{id}/reservation', [ProductOfferReservationController::class, 'create'])
            ->whereNumber('id');
        Route::put(
            '/trade_offer/reservation/{id}/status',
            [ProductOfferReservationController::class, 'updateStatus']
        )->whereNumber('id');
    });

    Route::get('/filter', [CatalogFilterController::class, 'getList']);


    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::get('/feature', [FeatureController::class, 'getList']);
        Route::get('/feature/{id}', [FeatureController::class, 'get'])->whereNumber('id');
        Route::post('/feature', [FeatureController::class, 'create']);
        Route::put('/feature/{id}', [FeatureController::class, 'update'])->whereNumber('id');
        Route::delete('/feature/{id}', [FeatureController::class, 'delete'])->whereNumber('id');
    });

    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::post('/product_feature', [ProductFeatureController::class, 'create']);
        Route::put('/product_feature/{uuid}', [ProductFeatureController::class, 'update'])
            ->whereUuid('uuid');
        Route::delete('/product_feature/{uuid}', [ProductFeatureController::class, 'delete'])
            ->whereUuid('uuid');
    });

    Route::middleware(['auth:sanctum', 'role:' . RoleEnum::ADMIN->value])->group(function () {
        Route::get('/seo', [SeoController::class, 'getList']);
        Route::get('/seo/{id}', [SeoController::class, 'get'])
            ->whereNumber('id');
        Route::post('/seo', [SeoController::class, 'create']);
        Route::put('/seo/{id}', [SeoController::class, 'update'])
            ->whereNumber('id');
        Route::delete('/seo/{id}', [SeoController::class, 'delete'])
            ->whereNumber('id');
    });
});
