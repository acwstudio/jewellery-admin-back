<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Blog\BlogCategories\BlogCategoryBlogPostsRelationshipsController;
use App\Http\Controllers\Admin\Blog\BlogCategories\BlogCategoryController;
use App\Http\Controllers\Admin\Blog\BlogPosts\BlogPostController;
use App\Http\Controllers\Admin\Blog\BlogCategories\BlogCategoryBlogPostsRelatedController;
use App\Http\Controllers\Admin\Blog\BlogPosts\BlogPostsBlogCategoryRelationshipsController;
use App\Http\Controllers\Admin\Blog\BlogPosts\BlogPostsBlogCategoryRelatedController;
use Illuminate\Http\Request;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

/*****************  BLOG CATEGORIES ROUTES **************/

// CRUD
Route::get('blog-categories', [BlogCategoryController::class, 'index'])->name('blog-categories.index');
Route::get('blog-categories/{id}', [BlogCategoryController::class, 'show'])->name('blog-categories.show');
Route::post('blog-categories', [BlogCategoryController::class, 'store'])->name('blog-categories.store');
Route::patch('blog-categories/{id}', [BlogCategoryController::class, 'update'])->name('blog-categories.update');
Route::delete('blog-categories/{id}', [BlogCategoryController::class, 'destroy'])->name('blog-categories.destroy');
//  one-to-many  BlogCategory to BlogPosts
Route::get('blog-categories/{id}/relationships/blog-posts', [BlogCategoryBlogPostsRelationshipsController::class, 'index'])
    ->name('blog-category.relationships.blog-posts');
Route::patch('blog-categories/{id}/relationships/blog-posts', [BlogCategoryBlogPostsRelationshipsController::class, 'update'])
    ->name('blog-category.relationships.blog-posts');
Route::get('blog-categories/{id}/blog-posts', [BlogCategoryBlogPostsRelatedController::class, 'index'])
    ->name('blog-category.blog-posts');

/*****************  BLOG POSTS ROUTES **************/
// CRUD
Route::get('blog-posts', [BlogPostController::class, 'index'])->name('blog-posts.index');
Route::get('blog-posts/{id}', [BlogPostController::class, 'show'])->name('blog-posts.show');
Route::post('blog-posts', [BlogPostController::class, 'store'])->name('blog-posts.store');
Route::patch('blog-posts/{id}', [BlogPostController::class, 'update'])->name('blog-posts.update');
Route::delete('blog-posts/{id}', [BlogPostController::class, 'destroy'])->name('blog-posts.destroy');
//  many-to-one BlogPosts to  BlogCategory
Route::get('blog-posts/{id}/relationships/blog-category', [BlogPostsBlogCategoryRelationshipsController::class, 'index'])
    ->name('blog-posts.relationships.blog-category');
Route::patch('blog-posts/{id}/relationships/blog-category', [BlogPostsBlogCategoryRelationshipsController::class, 'update'])
    ->name('blog-posts.relationships.blog-category');
Route::get('blog-posts/{id}/blog-category', [BlogPostsBlogCategoryRelatedController::class, 'index'])
    ->name('blog-posts.blog-category');
