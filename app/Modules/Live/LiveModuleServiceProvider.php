<?php

declare(strict_types=1);

namespace App\Modules\Live;

use App\Modules\Live\Contracts\Pipelines\LiveProductQueryBuilderPipelineContract;
use App\Modules\Live\Pipelines\LiveProductQueryBuilderPipeline;
use App\Modules\Live\Pipes\LiveProductQueryBuilderFilterPipe;
use App\Modules\Live\Pipes\LiveProductQueryBuilderSortPipe;
use App\Modules\Live\Services\LiveProductImportService;
use App\Modules\Live\Support\DataNormalizer\DataNormalizerInterface;
use App\Packages\ModuleClients\LiveModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class LiveModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        LiveModuleClientInterface::class => LiveModuleClient::class,
    ];

    private array $liveProductQueryBuilderPipes = [
        LiveProductQueryBuilderSortPipe::class,
        LiveProductQueryBuilderFilterPipe::class
    ];

    public function boot()
    {
        $this->registerQueryBuilderPipelines();
        $this->registerLiveProductImportService();
    }

    protected function registerQueryBuilderPipelines(): void
    {
        $this->app->singleton(
            LiveProductQueryBuilderPipelineContract::class,
            LiveProductQueryBuilderPipeline::class
        );
        $this->app->when(LiveProductQueryBuilderPipeline::class)->needs('$pipes')->give(
            fn () => $this->liveProductQueryBuilderPipes
        );
    }

    protected function registerLiveProductImportService(): void
    {
        $this->app
            ->when(LiveProductImportService::class)
            ->needs(DataNormalizerInterface::class)
            ->give(function () {
                return $this->app->make(config('live.import.live_product.data_normalizer'));
            });
    }
}
