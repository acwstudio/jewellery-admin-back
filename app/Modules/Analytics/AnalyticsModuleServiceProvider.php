<?php

declare(strict_types=1);

namespace App\Modules\Analytics;

use App\Packages\ModuleClients\AnalyticsModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class AnalyticsModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        AnalyticsModuleClientInterface::class => AnalyticsModuleClient::class,
    ];
}
