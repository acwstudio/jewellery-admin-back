<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Catalog\PriceCategories\PriceCategoriesProductsRelatedController;
use App\Http\Controllers\Admin\Catalog\PriceCategories\PriceCategoriesProductsRelationshipsController;
use App\Http\Controllers\Admin\Catalog\PriceCategories\PriceCategoryController;
use App\Http\Controllers\Admin\Catalog\PriceCategories\PriceCategoryPricesRelatedController;
use App\Http\Controllers\Admin\Catalog\PriceCategories\PriceCategoryPricesRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Prices\PriceController;
use App\Http\Controllers\Admin\Catalog\Prices\PricesPriceCategoryRelatedController;
use App\Http\Controllers\Admin\Catalog\Prices\PricesPriceCategoryRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Prices\PricesProductRelatedController;
use App\Http\Controllers\Admin\Catalog\Prices\PricesProductRelationshipsController;
use App\Http\Controllers\Admin\Catalog\ProductCategories\ProductCategoriesParentRelatedController;
use App\Http\Controllers\Admin\Catalog\ProductCategories\ProductCategoriesParentRelationshipsController;
use App\Http\Controllers\Admin\Catalog\ProductCategories\ProductCategoryChildrenRelatedController;
use App\Http\Controllers\Admin\Catalog\ProductCategories\ProductCategoryChildrenRelationshipsController;
use App\Http\Controllers\Admin\Catalog\ProductCategories\ProductCategoryController;
use App\Http\Controllers\Admin\Catalog\ProductCategories\ProductCategoryProductsRelatedController;
use App\Http\Controllers\Admin\Catalog\ProductCategories\ProductCategoryProductsRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsBlogPostsRelatedController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsBlogPostsRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsPriceCategoriesRelatedController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsPriceCategoriesRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsProductCategoryRelatedController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsProductCategoryRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Products\ProductController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsWeavesRelatedController;
use App\Http\Controllers\Admin\Catalog\Products\ProductsWeavesRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Sizes\SizeController;
use App\Http\Controllers\Admin\Catalog\Weaves\WeaveController;
use App\Http\Controllers\Admin\Catalog\Weaves\WeavesProductsRelatedController;
use App\Http\Controllers\Admin\Catalog\Weaves\WeavesProductsRelationshipsController;
use App\Http\Controllers\Admin\Catalog\Products\ProductPricesRelatedController;
use App\Http\Controllers\Admin\Catalog\Products\ProductPricesRelationshipsController;

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
Route::get('products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::post('products', [ProductController::class, 'store'])->name('products.store');
Route::patch('products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
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
//  many-to-many Products to Price Categories
Route::get('products/{id}/relationships/price-categories', [ProductsPriceCategoriesRelationshipsController::class, 'index'])
    ->name('products.relationships.price-categories');
Route::patch('products/{id}/relationships/price-categories', [ProductsPriceCategoriesRelationshipsController::class, 'update'])
    ->name('products.relationships.price-categories');
Route::get('products/{id}/price-categories', [ProductsPriceCategoriesRelatedController::class, 'index'])
    ->name('products.price-categories');
//  one-to-mane  Product to Prices
Route::get('products/{id}/relationships/prices', [ProductPricesRelationshipsController::class, 'index'])
    ->name('product.relationships.prices');
Route::patch('products/{id}/relationships/prices', [ProductPricesRelationshipsController::class, 'update'])
    ->name('product.relationships.prices');
Route::get('products/{id}/prices', [ProductPricesRelatedController::class, 'index'])
    ->name('product.prices');

/*****************  PRODUCT CATEGORIES ROUTES **************/
// CRUD
Route::get('product-categories', [ProductCategoryController::class, 'index'])->name('product-categories.index');
Route::get('product-categories/{id}', [ProductCategoryController::class, 'show'])->name('product-categories.show');
Route::post('product-categories', [ProductCategoryController::class, 'store'])->name('product-categories.store');
Route::patch('product-categories/{id}', [ProductCategoryController::class, 'update'])->name('product-categories.update');
Route::delete('product-categories/{id}', [ProductCategoryController::class, 'destroy'])->name('product-categories.destroy');
//  one-to-many  Product Categories to Products
Route::get('product-categories/{id}/relationships/products', [ProductCategoryProductsRelationshipsController::class, 'index'])
    ->name('product-category.relationships.products');
Route::patch('product-categories/{id}/relationships/products', [ProductCategoryProductsRelationshipsController::class, 'update'])
    ->name('product-category.relationships.products');
Route::get('product-categories/{id}/products', [ProductCategoryProductsRelatedController::class, 'index'])
    ->name('product-category.products');
//  many-to-one  Product Category to parent
Route::get('product-categories/{id}/relationships/parent', [ProductCategoriesParentRelationshipsController::class, 'index'])
    ->name('product-categories.relationships.parent');
Route::patch('product-categories/{id}/relationships/parent', [ProductCategoriesParentRelationshipsController::class, 'update'])
    ->name('product-categories.relationships.parent');
Route::get('product-categories/{id}/parent', [ProductCategoriesParentRelatedController::class, 'index'])
    ->name('product-categories.parent');
//  one-to-many  Product Category to children
Route::get('product-categories/{id}/relationships/children', [ProductCategoryChildrenRelationshipsController::class, 'index'])
    ->name('product-category.relationships.children');
Route::patch('product-categories/{id}/relationships/children', [ProductCategoryChildrenRelationshipsController::class, 'update'])
    ->name('product-category.relationships.children');
Route::get('product-categories/{id}/children', [ProductCategoryChildrenRelatedController::class, 'index'])
    ->name('product-category.children');

/*****************  WEAVES ROUTES **************/
// CRUD
Route::get('weaves', [WeaveController::class, 'index'])->name('weaves.index');
Route::get('weaves/{id}', [WeaveController::class, 'show'])->name('weaves.show');
Route::post('weaves', [WeaveController::class, 'store'])->name('weaves.store');
Route::patch('weaves/{id}', [WeaveController::class, 'update'])->name('weaves.update');
Route::delete('weaves/{id}', [WeaveController::class, 'destroy'])->name('weaves.destroy');
//  many-to-many  Weaves to Products
Route::get('weaves/{id}/relationships/products', [WeavesProductsRelationshipsController::class, 'index'])
    ->name('weaves.relationships.products');
Route::patch('weaves/{id}/relationships/products', [WeavesProductsRelationshipsController::class, 'update'])
    ->name('weaves.relationships.products');
Route::get('weaves/{id}/products', [WeavesProductsRelatedController::class, 'index'])
    ->name('weaves.products');

/*****************  PRICES ROUTES **************/
// CRUD
Route::get('prices', [PriceController::class, 'index'])->name('prices.index');
Route::get('prices/{id}', [PriceController::class, 'show'])->name('prices.show');
Route::post('prices', [PriceController::class, 'store'])->name('prices.store');
Route::patch('prices/{id}', [PriceController::class, 'update'])->name('prices.update');
Route::delete('prices/{id}', [PriceController::class, 'destroy'])->name('prices.destroy');
//  many-to-one  Prices to Product
Route::get('prices/{id}/relationships/product', [PricesProductRelationshipsController::class, 'index'])
    ->name('prices.relationships.product');
Route::patch('prices/{id}/relationships/product', [PricesProductRelationshipsController::class, 'update'])
    ->name('prices.relationships.product');
Route::get('prices/{id}/product', [PricesProductRelatedController::class, 'index'])
    ->name('prices.product');
//  many-to-one  Prices to Price Category
Route::get('prices/{id}/relationships/price-category', [PricesPriceCategoryRelationshipsController::class, 'index'])
    ->name('prices.relationships.price-category');
Route::patch('prices/{id}/relationships/price-category', [PricesPriceCategoryRelationshipsController::class, 'update'])
    ->name('prices.relationships.price-category');
Route::get('prices/{id}/price-category', [PricesPriceCategoryRelatedController::class, 'index'])
    ->name('prices.price-category');

/*****************  PRICE CATEGORIES ROUTES **************/
// CRUD
Route::get('price-categories', [PriceCategoryController::class, 'index'])->name('price-categories.index');
Route::get('price-categories/{id}', [PriceCategoryController::class, 'show'])->name('price-categories.show');
Route::post('price-categories', [PriceCategoryController::class, 'store'])->name('price-categories.store');
Route::patch('price-categories/{id}', [PriceCategoryController::class, 'update'])->name('price-categories.update');
Route::delete('price-categories/{id}', [PriceCategoryController::class, 'destroy'])->name('price-categories.destroy');
//  many-to-many  PriceCategories to Products
Route::get('price-categories/{id}/relationships/products', [PriceCategoriesProductsRelationshipsController::class, 'index'])
    ->name('price-categories.relationships.products');
Route::patch('price-categories/{id}/relationships/products', [PriceCategoriesProductsRelationshipsController::class, 'update'])
    ->name('price-categories.relationships.products');
Route::get('price-categories/{id}/products', [PriceCategoriesProductsRelatedController::class, 'index'])
    ->name('price-categories.products');
//  many-to-one  Prices to Price Category
Route::get('price-categories/{id}/relationships/prices', [PriceCategoryPricesRelationshipsController::class, 'index'])
    ->name('price-category.relationships.prices');
Route::patch('price-categories/{id}/relationships/prices', [PriceCategoryPricesRelationshipsController::class, 'update'])
    ->name('price-category.relationships.prices');
Route::get('price-categories/{id}/prices', [PriceCategoryPricesRelatedController::class, 'index'])
    ->name('price-category.prices');

/******************* SIZES ROUTES ****************************/
// CRUD
Route::get('sizes', [SizeController::class, 'index'])->name('sizess.index');
