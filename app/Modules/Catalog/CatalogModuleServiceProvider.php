<?php

declare(strict_types=1);

namespace App\Modules\Catalog;

use App\Modules\Catalog\Contracts\Pipelines\CategoryQueryBuilderPipelineContract;
use App\Modules\Catalog\Contracts\Pipelines\FeatureQueryBuilderPipelineContract;
use App\Modules\Catalog\Contracts\Pipelines\ProductQueryBuilderPipelineContract;
use App\Modules\Catalog\Contracts\Pipelines\ProductScoutBuilderPipelineContract;
use App\Modules\Catalog\Pipelines\CategoryQueryBuilderPipeline;
use App\Modules\Catalog\Pipelines\FeatureQueryBuilderPipeline;
use App\Modules\Catalog\Pipelines\ProductQueryBuilderPipeline;
use App\Modules\Catalog\Pipelines\ProductScoutBuilderPipeline;
use App\Modules\Catalog\Pipes\FilterCategoryQueryBuilderPipe;
use App\Modules\Catalog\Pipes\FilterFeatureQueryBuilderPipe;
use App\Modules\Catalog\Pipes\FilterProductQueryBuilderPipe;
use App\Modules\Catalog\Pipes\ProductQueryBuilderHasOfferPipe;
use App\Modules\Catalog\Pipes\ProductQueryBuilderSortPipe;
use App\Modules\Catalog\Pipes\ProductScoutBuilderFilterPipe;
use App\Modules\Catalog\Pipes\ProductScoutBuilderSortPipe;
use App\Modules\Catalog\Services\CategoryImportService;
use App\Modules\Catalog\Services\Import\ProductImportService;
use App\Modules\Catalog\Services\Import\ProductLiveImportService;
use App\Modules\Catalog\Services\Import\ProductOfferPriceLiveImportService;
use App\Modules\Catalog\Services\Import\ProductOfferPriceRegularImportService;
use App\Modules\Catalog\Services\Import\ProductOfferStockImportService;
use App\Modules\Catalog\Services\ProductFilterImportService;
use App\Modules\Catalog\Support\DataNormalizer\DataNormalizerInterface;
use App\Modules\Catalog\Support\DataProvider\DataProviderInterface;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class CatalogModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        CatalogModuleClientInterface::class => CatalogModuleClient::class,
    ];

    private array $productQueryBuilderPipes = [
        ProductQueryBuilderSortPipe::class,
        ProductQueryBuilderHasOfferPipe::class,
        FilterProductQueryBuilderPipe::class
    ];

    private array $productScoutBuilderPipes = [
        ProductScoutBuilderFilterPipe::class,
        ProductScoutBuilderSortPipe::class
    ];

    private array $categoryQueryBuilderPipes = [
        FilterCategoryQueryBuilderPipe::class
    ];

    private array $featureQueryBuilderPipes = [
        FilterFeatureQueryBuilderPipe::class
    ];

    public function boot()
    {
        $this->registerQueryBuilderPipelines();
        $this->registerCategoryImportService();
        $this->registerProductImportService();
        $this->registerProductFilterImportService();
        $this->registerProductOfferPriceImportService();
        $this->registerProductOfferStockImportService();
        $this->registerProductLiveImportService();
        $this->registerProductOfferPriceRegularImportService();
    }

    protected function registerQueryBuilderPipelines(): void
    {
        $this->app->singleton(
            ProductQueryBuilderPipelineContract::class,
            ProductQueryBuilderPipeline::class
        );
        $this->app->when(ProductQueryBuilderPipeline::class)->needs('$pipes')->give(
            fn () => $this->productQueryBuilderPipes
        );

        $this->app->singleton(
            FeatureQueryBuilderPipelineContract::class,
            FeatureQueryBuilderPipeline::class
        );
        $this->app->when(FeatureQueryBuilderPipeline::class)->needs('$pipes')->give(
            fn () => $this->featureQueryBuilderPipes
        );

        $this->app->singleton(
            CategoryQueryBuilderPipelineContract::class,
            CategoryQueryBuilderPipeline::class
        );
        $this->app->when(CategoryQueryBuilderPipeline::class)->needs('$pipes')->give(
            fn () => $this->categoryQueryBuilderPipes
        );

        $this->app->singleton(
            ProductScoutBuilderPipelineContract::class,
            ProductScoutBuilderPipeline::class
        );
        $this->app->when(ProductScoutBuilderPipeline::class)->needs('$pipes')->give(
            fn () => $this->productScoutBuilderPipes
        );
    }

    protected function registerCategoryImportService(): void
    {
        if (!$this->app->runningUnitTests()) {
            $this->app
                ->when(CategoryImportService::class)
                ->needs(DataProviderInterface::class)
                ->give(function () {
                    return $this->app->make(config('catalog.import.category.data_provider'));
                });
        }

        $this->app
            ->when(CategoryImportService::class)
            ->needs(DataNormalizerInterface::class)
            ->give(function () {
                return $this->app->make(config('catalog.import.category.data_normalizer'));
            });
    }

    protected function registerProductImportService(): void
    {
        $this->app
            ->when(ProductImportService::class)
            ->needs(DataNormalizerInterface::class)
            ->give(function () {
                return $this->app->make(config('catalog.import.product.data_normalizer'));
            });
    }

    protected function registerProductFilterImportService(): void
    {
        if (!$this->app->runningUnitTests()) {
            $this->app
                ->when(ProductFilterImportService::class)
                ->needs(DataProviderInterface::class)
                ->give(function () {
                    return $this->app->make(config('catalog.import.product_filter.data_provider'));
                });
        }

        $this->app
            ->when(ProductFilterImportService::class)
            ->needs(DataNormalizerInterface::class)
            ->give(function () {
                return $this->app->make(config('catalog.import.product_filter.data_normalizer'));
            });
    }

    protected function registerProductOfferPriceImportService(): void
    {
        $this->app
            ->when(ProductOfferPriceLiveImportService::class)
            ->needs(DataNormalizerInterface::class)
            ->give(function () {
                return $this->app->make(config('catalog.import.product_offer_price_live.data_normalizer'));
            });
    }

    protected function registerProductOfferStockImportService(): void
    {
        $this->app
            ->when(ProductOfferStockImportService::class)
            ->needs(DataNormalizerInterface::class)
            ->give(function () {
                return $this->app->make(config('catalog.import.product_offer_stock.data_normalizer'));
            });
    }

    protected function registerProductLiveImportService(): void
    {
        $this->app
            ->when(ProductLiveImportService::class)
            ->needs(DataNormalizerInterface::class)
            ->give(function () {
                return $this->app->make(config('catalog.import.product_live.data_normalizer'));
            });
    }

    protected function registerProductOfferPriceRegularImportService(): void
    {
        $this->app
            ->when(ProductOfferPriceRegularImportService::class)
            ->needs(DataNormalizerInterface::class)
            ->give(function () {
                return $this->app->make(config('catalog.import.product_offer_price_regular.data_normalizer'));
            });
    }
}
