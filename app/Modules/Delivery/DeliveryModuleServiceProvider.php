<?php

declare(strict_types=1);

namespace App\Modules\Delivery;

use App\Modules\Delivery\Services\PvzCacheService;
use App\Modules\Delivery\Services\PvzService;
use App\Modules\Delivery\Support\Pvz\DataNormalizer\Enterprise1CPvzDataNormalizer;
use App\Modules\Delivery\Support\Pvz\Filter\CarrierFilterPipe;
use App\Modules\Delivery\Support\Pvz\Filter\DistrictFilterPipe;
use App\Modules\Delivery\Support\Pvz\Filter\StreetFilterPipe;
use App\Modules\Delivery\UseCase\GetPvz;
use App\Modules\Delivery\Support\Pvz\DataNormalizer\PvzDataNormalizerInterface;
use App\Modules\Delivery\Support\Pvz\DataProvider\AMQPPvzDataProvider;
use App\Modules\Delivery\Support\Pvz\DataProvider\PvzDataProviderInterface;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class DeliveryModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        DeliveryModuleClientInterface::class => DeliveryModuleClient::class,
        PvzDataProviderInterface::class => AMQPPvzDataProvider::class,
        PvzDataNormalizerInterface::class => Enterprise1CPvzDataNormalizer::class,
    ];

    private array $getPvzFilterPipes = [
        CarrierFilterPipe::class,
        StreetFilterPipe::class,
        DistrictFilterPipe::class
    ];

    public function boot(): void
    {
        $this->registerGetPvzUseCase();
    }

    private function registerGetPvzUseCase(): void
    {
        $this->app->bind(GetPvz::class, function () {
            return new GetPvz(
                $this->app->make(PvzService::class),
                $this->app->make(PvzCacheService::class),
                $this->getPvzFilterPipes
            );
        });
    }
}
