<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Catalog\ProductCategories\ProductCategoryController;
use App\Http\Controllers\Admin\Catalog\ProductCategories\ProductCategoryProductsRelatedController;
use App\Http\Controllers\Admin\Catalog\ProductCategories\ProductCategoryProductsRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsBlogPostsRelatedController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsBlogPostsRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsProductCategoryRelatedController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsProductCategoryRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Products\ProductController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsWeavesRelatedController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsWeavesRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Weaves\WeaveController;
use App\Http\Controllers\Admin\Catalog\Weaves\WeavesProductsRelatedController;
use App\Http\Controllers\Admin\Catalog\Weaves\WeavesProductsRelationshipsController;

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

/*****************  PRODUCTS ROUTES **************/
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
//  many-to-many  Products to Weaves
Route::get('products/{id}/relationships/weaves', [ProductsWeavesRelationshipsController::class, 'index'])
    ->name('products.relationships.weaves');
Route::patch('products/{id}/relationships/weaves', [ProductsWeavesRelationshipsController::class, 'update'])
    ->name('products.relationships.weaves');
Route::get('products/{id}/weaves', [ProductsWeavesRelatedController::class, 'index'])
    ->name('products.weaves');
//  many-to-many  Products to Blog Posts
Route::get('products/{id}/relationships/blog-posts', [ProductsBlogPostsRelationshipsController::class, 'index'])
    ->name('products.relationships.blog-posts');
Route::patch('products/{id}/relationships/blog-posts', [ProductsBlogPostsRelationshipsController::class, 'update'])
    ->name('products.relationships.blog-posts');
Route::get('products/{id}/blog-posts', [ProductsBlogPostsRelatedController::class, 'index'])
    ->name('products.blog-posts');

/*****************  PRODUCT CATEGORIES ROUTES **************/
// CRUD
Route::get('product-categories', [ProductCategoryController::class, 'index'])->name('product-categories.index');
Route::get('product-categories/{id}', [ProductCategoryController::class, 'index'])->name('product-categories.show');
Route::post('product-categories', [ProductCategoryController::class, 'index'])->name('product-categories.store');
Route::patch('product-categories/{id}', [ProductCategoryController::class, 'index'])->name('product-categories.update');
Route::delete('product-categories/{id}', [ProductCategoryController::class, 'index'])->name('product-categories.destroy');
//  one-to-many  Product Categories to Products
Route::get('product-categories/{id}/relationships/products', [ProductCategoryProductsRelationshipsController::class, 'index'])
    ->name('product-category.relationships.products');
Route::patch('product-categories/{id}/relationships/products', [ProductCategoryProductsRelationshipsController::class, 'update'])
    ->name('product-category.relationships.products');
Route::get('product-categories/{id}/products', [ProductCategoryProductsRelatedController::class, 'index'])
    ->name('product-category.products');

/*****************  WEAVES ROUTES **************/
// CRUD
Route::get('weaves', [WeaveController::class, 'index'])->name('weaves.index');
Route::get('weaves/{id}', [WeaveController::class, 'index'])->name('weaves.show');
Route::post('weaves', [WeaveController::class, 'index'])->name('weaves.store');
Route::patch('weaves/{id}', [WeaveController::class, 'index'])->name('weaves.update');
Route::delete('weaves/{id}', [WeaveController::class, 'index'])->name('weaves.destroy');
//  many-to-many  Weaves to Products
Route::get('weaves/{id}/relationships/products', [WeavesProductsRelationshipsController::class, 'index'])
    ->name('weaves.relationships.products');
Route::patch('weaves/{id}/relationships/products', [WeavesProductsRelationshipsController::class, 'update'])
    ->name('weaves.relationships.products');
Route::get('weaves/{id}/products', [WeavesProductsRelatedController::class, 'index'])
    ->name('weaves.products');