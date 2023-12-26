<?php

declare(strict_types=1);

namespace App\Modules\Orders;

use App\Modules\Orders\Contracts\Pipelines\OrderQueryBuilderPipelineContract;
use App\Modules\Orders\Pipelines\OrderQueryBuilderPipeline;
use App\Modules\Orders\Pipes\OrderQueryBuilderFilterPipe;
use App\Modules\Orders\Pipes\OrderQueryBuilderSortPipe;
use App\Packages\ModuleClients\OrdersModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class OrdersModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        OrdersModuleClientInterface::class => OrdersModuleClient::class,
    ];

    private array $orderQueryBuilderPipes = [
        OrderQueryBuilderFilterPipe::class,
        OrderQueryBuilderSortPipe::class
    ];

    public function boot(): void
    {
        $this->registerQueryBuilderPipelines();
    }

    protected function registerQueryBuilderPipelines(): void
    {
        $this->app->singleton(
            OrderQueryBuilderPipelineContract::class,
            OrderQueryBuilderPipeline::class
        );
        $this->app->when(OrderQueryBuilderPipeline::class)->needs('$pipes')->give(
            fn() => $this->orderQueryBuilderPipes
        );
    }
}
