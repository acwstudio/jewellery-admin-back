<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\AddProductFeatureCollectionListener;
use App\Listeners\ChangeProductOfferStock;
use App\Listeners\ClearShopCart;
use App\Listeners\CreateProductOfferPriceSaleListener;
use App\Listeners\CreateSaleListener;
use App\Listeners\ExportSurveyMessage;
use App\Listeners\PublishCompletedSurvey;
use App\Listeners\PublishOrder1C;
use App\Listeners\PublishPaymentStatus1C;
use App\Listeners\RedirectProductOfferPricesImportedMessage;
use App\Listeners\SendOtpVerificationCode;
use App\Listeners\SetWishListWebsiteListener;
use App\Listeners\UpdatePromocodeUsage;
use App\Listeners\UpdateUserData;
use App\Listeners\WarmUpCatalogFilterCache;
use App\Packages\Events\CompletedSurveyCreated;
use App\Packages\Events\OrderCreated;
use App\Packages\Events\OtpVerificationCreated;
use App\Packages\Events\PaymentStatusChanged;
use App\Packages\Events\ProductOfferReservationStatusChanged;
use App\Packages\Events\PromotionCreated;
use App\Packages\Events\Sync\CategoriesImported;
use App\Packages\Events\Sync\CollectionsImported;
use App\Packages\Events\Sync\ProductFiltersImported;
use App\Packages\Events\Sync\ProductOfferPricesImported;
use App\Packages\Events\Sync\ProductOfferStocksImported;
use App\Packages\Events\Sync\ProductsImported;
use App\Packages\Events\Sync\SendSurvey1C;
use App\Packages\Events\WishlistProductChanged;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ProductOfferReservationStatusChanged::class => [
            ChangeProductOfferStock::class,
        ],
        OtpVerificationCreated::class => [
            SendOtpVerificationCode::class,
        ],
        OrderCreated::class => [
            UpdatePromocodeUsage::class,
            UpdateUserData::class,
            PublishOrder1C::class,
            ClearShopCart::class,
        ],
        CategoriesImported::class => [
//            WarmUpCatalogFilterCache::class,
        ],
        ProductFiltersImported::class => [
//            WarmUpCatalogFilterCache::class,
        ],
        ProductOfferPricesImported::class => [
//            WarmUpCatalogFilterCache::class,
            RedirectProductOfferPricesImportedMessage::class,
        ],
        ProductOfferStocksImported::class => [
//            WarmUpCatalogFilterCache::class,
        ],
        ProductsImported::class => [
//            WarmUpCatalogFilterCache::class,
        ],
        CompletedSurveyCreated::class => [
            PublishCompletedSurvey::class,
        ],
        WishlistProductChanged::class => [
            SetWishListWebsiteListener::class
        ],
        PromotionCreated::class => [
            CreateSaleListener::class,
            CreateProductOfferPriceSaleListener::class
        ],
        SendSurvey1C::class => [
            ExportSurveyMessage::class
        ],
        PaymentStatusChanged::class => [
            PublishPaymentStatus1C::class
        ],
        CollectionsImported::class => [
            AddProductFeatureCollectionListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
