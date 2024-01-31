<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Catalog\ProductCategories\ProductsProductCategoryRelatedController;
use App\Http\Controllers\Admin\Catalog\ProductCategories\ProductsProductCategoryRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Products\ProductController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

/*****************  PRODUCT ROUTES **************/

// CRUD
Route::get('products', [ProductController::class, 'index'])->name('products.index');
Route::get('products/{id}', [ProductController::class, 'index'])->name('products.show');
Route::post('products', [ProductController::class, 'index'])->name('products.store');
Route::patch('products/{id}', [ProductController::class, 'index'])->name('products.update');
Route::delete('products/{id}', [ProductController::class, 'index'])->name('products.destroy');
//  many-to-one  Products to ProductCategory
Route::get('products/{id}/relationships/product-category', [ProductsProductCategoryRelationshipsController::class, 'index'])
    ->name('products.relationships.product-category');
Route::patch('products/{id}/relationships/product-category', [ProductsProductCategoryRelationshipsController::class, 'update'])
    ->name('products.relationships.product-category');
Route::get('products/{id}/product-category', [ProductsProductCategoryRelatedController::class, 'index'])
    ->name('products.product-category');
