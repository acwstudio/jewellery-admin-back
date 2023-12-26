<?php

declare(strict_types=1);

namespace App\Modules\Collections;

use App\Modules\Collections\Contracts\Pipelines\CollectionQueryBuilderPipelineContract;
use App\Modules\Collections\Contracts\Pipelines\StoneQueryBuilderPipelineContract;
use App\Modules\Collections\Pipelines\CollectionQueryBuilderPipeline;
use App\Modules\Collections\Pipelines\StoneQueryBuilderPipeline;
use App\Modules\Collections\Pipes\FilterCollectionQueryBuilderPipe;
use App\Modules\Collections\Pipes\FilterStoneQueryBuilderPipe;
use App\Modules\Collections\Services\CollectionProductImportService;
use App\Modules\Collections\Services\Import\ImportCollectionService;
use App\Modules\Collections\Support\DataNormalizer\DataNormalizerInterface;
use App\Modules\Collections\Support\DataProvider\DataProviderInterface;
use App\Packages\ModuleClients\CollectionModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class CollectionModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        CollectionModuleClientInterface::class => CollectionModuleClient::class,
    ];

    private array $collectionQueryBuilderPipes = [
        FilterCollectionQueryBuilderPipe::class
    ];

    private array $stoneQueryBuilderPipes = [
        FilterStoneQueryBuilderPipe::class
    ];

    public function boot()
    {
        $this->registerQueryBuilderPipelines();
        $this->registerCollectionProductImportService();
    }

    protected function registerQueryBuilderPipelines(): void
    {
        $this->app->singleton(
            CollectionQueryBuilderPipelineContract::class,
            CollectionQueryBuilderPipeline::class
        );
        $this->app->when(CollectionQueryBuilderPipeline::class)->needs('$pipes')->give(
            fn () => $this->collectionQueryBuilderPipes
        );

        $this->app->singleton(
            StoneQueryBuilderPipelineContract::class,
            StoneQueryBuilderPipeline::class
        );
        $this->app->when(StoneQueryBuilderPipeline::class)->needs('$pipes')->give(
            fn () => $this->stoneQueryBuilderPipes
        );
    }

    protected function registerCollectionProductImportService(): void
    {
        if (!$this->app->runningUnitTests()) {
            $this->app
                ->when(CollectionProductImportService::class)
                ->needs(DataProviderInterface::class)
                ->give(function () {
                    return $this->app->make(config('collections.import.products.data_provider'));
                });
        }

        $this->app
            ->when(CollectionProductImportService::class)
            ->needs(DataNormalizerInterface::class)
            ->give(function () {
                return $this->app->make(config('collections.import.products.data_normalizer'));
            });

        $this->app
            ->when(ImportCollectionService::class)
            ->needs(DataNormalizerInterface::class)
            ->give(function () {
                return $this->app->make(config('collections.import.collections.data_normalizer'));
            });
    }
}
