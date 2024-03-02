<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\Performance\Banners\BannerController;
use App\Http\Controllers\Admin\Performance\Banners\BannersImageBannersRelatedController;
use App\Http\Controllers\Admin\Performance\Banners\BannersImageBannersRelationshipsController;
use App\Http\Controllers\Admin\Performance\Banners\BannersTypeBannerRelatedController;
use App\Http\Controllers\Admin\Performance\Banners\BannersTypeBannerRelationshipsController;
use App\Http\Controllers\Admin\Performance\Banners\BannersTypePageRelatedController;
use App\Http\Controllers\Admin\Performance\Banners\BannersTypePageRelationshipsController;
use App\Http\Controllers\Admin\Performance\ImageBanners\ImageBannerController;
use App\Http\Controllers\Admin\Performance\ImageBanners\ImageBannersBannersRelatedController;
use App\Http\Controllers\Admin\Performance\ImageBanners\ImageBannersBannersRelationshipsController;
use App\Http\Controllers\Admin\Performance\ImageBanners\ImageBannersTypeDeviceRelatedController;
use App\Http\Controllers\Admin\Performance\ImageBanners\ImageBannersTypeDeviceRelationshipsController;
use App\Http\Controllers\Admin\Performance\ImageStorage\ImageStorageController;
use App\Http\Controllers\Admin\Performance\TypeBanners\TypeBannerBannersRelatedController;
use App\Http\Controllers\Admin\Performance\TypeBanners\TypeBannerBannersRelationshipsController;
use App\Http\Controllers\Admin\Performance\TypeBanners\TypeBannerController;
use App\Http\Controllers\Admin\Performance\TypeDevices\TypeDeviceController;
use App\Http\Controllers\Admin\Performance\TypeDevices\TypeDeviceImageBannersRelatedController;
use App\Http\Controllers\Admin\Performance\TypeDevices\TypeDeviceImageBannersRelationshipsController;
use App\Http\Controllers\Admin\Performance\TypePages\TypePageBannersRelatedController;
use App\Http\Controllers\Admin\Performance\TypePages\TypePageBannersRelationshipsController;
use App\Http\Controllers\Admin\Performance\TypePages\TypePageController;

//**********************************************************************************************************
//*************************PERFORMANCE DOMAIN ROUTES********************************************************
//*********************************************************************************************************//

//************************BANNERS ROUTES******************************************
    /** CRUD */

Route::withoutMiddleware(["auth"])->group(function () {
    Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
    Route::get('/banners/{id}', [BannerController::class, 'show'])->name('banners.show');
    Route::post('/banners', [BannerController::class, 'store'])->name('banners.store');
    Route::patch('/banners/{id}', [BannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{id}', [BannerController::class, 'destroy'])->name('banners.destroy');
    /** many-to-one Banners to Type Banner */
    Route::get('/banners/{id}/relationships/type-banner', [BannersTypeBannerRelationshipsController::class, 'index'])
        ->name('banners.relationships.type-banner');
    Route::patch('/banners/{id}/relationships/type-banner', [BannersTypeBannerRelationshipsController::class, 'update'])
        ->name('banners.relationships.type-banner');
    Route::get('/banners/{id}/type-banner', [BannersTypeBannerRelatedController::class, 'index'])
        ->name('banners.type-banner');
    /** many-to-many Banners tp ImageBanners */
    Route::get(
        '/banners/{id}/relationships/image-banners',
        [BannersImageBannersRelationshipsController::class, 'index']
    )->name('banners.relationships.image-banners');
    Route::patch(
        '/banners/{id}/relationships/image-banners',
        [BannersImageBannersRelationshipsController::class, 'update']
    )->name('banners.relationships.image-banners');
    Route::get('/banners/{id}/image-banners', [BannersImageBannersRelatedController::class, 'index'])
        ->name('banners.image-banners');
    /** many-to-one Banners to Type Page */
    Route::get('/banners/{id}/relationships/type-page', [BannersTypePageRelationshipsController::class, 'index'])
        ->name('banners.relationships.type-page');
    Route::patch('/banners/{id}/relationships/type-page', [BannersTypePageRelationshipsController::class, 'update'])
        ->name('banners.relationships.type-page');
    Route::get('/banners/{id}/type-page', [BannersTypePageRelatedController::class, 'index'])
        ->name('banners.type-page');
});

//************************IMAGE BANNERS ROUTES******************************************
Route::withoutMiddleware(["auth"])->group(function () {
    /** CRUD */
    Route::get('/image-banners', [ImageBannerController::class, 'index'])->name('image-banners.index');
    Route::get('/image-banners/{id}', [ImageBannerController::class, 'show'])->name('image-banners.show');
    Route::post('/image-banners', [ImageBannerController::class, 'store'])->name('image-banners.store');
    Route::patch('/image-banners/{id}', [ImageBannerController::class, 'update'])->name('image-banners.update');
    Route::delete('/image-banners/{id}', [ImageBannerController::class, 'destroy'])->name('image-banners.destroy');
    /** many-to-many Image Banners to Banners */
    Route::get(
        '/image-banners/{id}/relationships/banners',
        [ImageBannersBannersRelationshipsController::class, 'index']
    )->name('image-banners.relationships.banners');
    Route::patch(
        '/image-banners/{id}/relationships/banners',
        [ImageBannersBannersRelationshipsController::class, 'update']
    )->name('image-banners.relationships.banners');
    Route::get('/image-banners/{id}/banners', [ImageBannersBannersRelatedController::class, 'index'])
        ->name('image-banners.banners');
    /** many-to-one Image Banners to Type Device */
    Route::get(
        '/image-banners/{id}/relationships/type-device',
        [ImageBannersTypeDeviceRelationshipsController::class, 'index']
    )->name('image-banners.relationships.type-device');
    Route::patch(
        '/image-banners/{id}/relationships/type-device',
        [ImageBannersTypeDeviceRelationshipsController::class, 'update']
    )->name('image-banners.relationships.type-device.update');
    Route::get('/image-banners/{id}/type-device', [ImageBannersTypeDeviceRelatedController::class, 'index'])
        ->name('image-banners.type-device');
});

//************************IMAGE STORAGE ROUTES******************************************
Route::withoutMiddleware(["auth"])->group(function () {
    /** CRUD */
    Route::get('/image-storages', [ImageStorageController::class, 'index'])->name('image-storages.index');
    Route::get('/image-storages/{id}', [ImageStorageController::class, 'show'])->name('image-storages.show');
    Route::post('/image-storages', [ImageStorageController::class, 'store'])->name('image-storages.store');
    Route::patch('/image-storages', [ImageStorageController::class, 'update'])->name('image-storages.update');
    Route::delete('/image-storages', [ImageStorageController::class, 'destroy'])->name('image-storages.destroy');
});

//************************TYPE DEVICES ROUTES******************************************
Route::withoutMiddleware(["auth"])->group(function () {
    /** CRUD */
    Route::get('/type-devices', [TypeDeviceController::class, 'index'])->name('type-devices.index');
    Route::get('/type-devices/{id}', [TypeDeviceController::class, 'show'])->name('type-devices.show');
    Route::post('/type-devices', [TypeDeviceController::class, 'store'])->name('type-devices.store');
    Route::patch('/type-devices/{id}', [TypeDeviceController::class, 'update'])->name('type-devices.update');
    Route::delete('/type-devices/{id}', [TypeDeviceController::class, 'destroy'])->name('type-devices.destroy');
    /** one-to-many Type Device tp Image Banners */
    Route::get(
        '/type-devices/{id}/relationships/image-banners',
        [TypeDeviceImageBannersRelationshipsController::class, 'index']
    )->name('type-device.relationships.image-banners');
    Route::patch(
        '/type-devices/{id}/relationships/image-banners',
        [TypeDeviceImageBannersRelationshipsController::class, 'update']
    )->name('type-device.relationships.image-banners.update');
    Route::get('/type-devices/{id}/image-banners', [TypeDeviceImageBannersRelatedController::class, 'index'])
        ->name('type-device.image-banners');
});

//************************TYPE BANNERS ROUTES******************************************
Route::withoutMiddleware(["auth"])->group(function () {
    /** CRUD */
    Route::get('/type-banners', [TypeBannerController::class, 'index'])->name('type-banners.index');
    Route::get('/type-banners/{id}', [TypeBannerController::class, 'show'])->name('type-banners.show');
    Route::post('/type-banners', [TypeBannerController::class, 'store'])->name('type-banners.store');
    Route::patch('/type-banners/{id}', [TypeBannerController::class, 'update'])->name('type-banners.update');
    Route::delete('/type-banners/{id}', [TypeBannerController::class, 'destroy'])->name('type-banners.destroy');
    /** one-to-many Type Banner tp Banners */
    Route::get('/type-banners/{id}/relationships/banners', [TypeBannerBannersRelationshipsController::class, 'index'])
        ->name('type-banner.relationships.banners');
    Route::patch(
        '/type-banners/{id}/relationships/banners',
        [TypeBannerBannersRelationshipsController::class, 'update']
    )->name('type-banner.relationships.banners.update');
    Route::get('/type-banners/{id}/banners', [TypeBannerBannersRelatedController::class, 'index'])
        ->name('type-banner.banners');
});

//************************TYPE PAGES ROUTES******************************************
Route::withoutMiddleware(["auth"])->group(function () {
    /** CRUD */
    Route::get('/type-pages', [TypePageController::class, 'index'])->name('type-pages.index');
    Route::get('/type-pages/{id}', [TypePageController::class, 'show'])->name('type-pages.show');
    Route::post('/type-pages', [TypePageController::class, 'store'])->name('type-pages.store');
    Route::patch('/type-pages/{id}', [TypePageController::class, 'update'])->name('type-pages.update');
    Route::delete('/type-pages/{id}', [TypePageController::class, 'destroy'])->name('type-pages.destroy');
    /** one-to-many Type Page tp Banners */
    Route::get('/type-pages/{id}/relationships/banners', [TypePageBannersRelationshipsController::class, 'index'])
        ->name('type-page.relationships.banners');
    Route::patch('/type-pages/{id}/relationships/banners', [TypePageBannersRelationshipsController::class, 'update'])
        ->name('type-page.relationships.banners.update');
    Route::get('/type-pages/{id}/banners', [TypePageBannersRelatedController::class, 'index'])
        ->name('type-page.banners');
});
