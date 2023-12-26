<?php

declare(strict_types=1);

namespace App\Modules\Monolith;

use App\Packages\ModuleClients\MonolithModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class MonolithModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        MonolithModuleClientInterface::class => MonolithModuleClient::class,
    ];
}
