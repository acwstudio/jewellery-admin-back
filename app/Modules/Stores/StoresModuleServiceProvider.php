<?php

declare(strict_types=1);

namespace App\Modules\Stores;

use App\Packages\ModuleClients\StoresModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class StoresModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        StoresModuleClientInterface::class => StoresModuleClient::class,
    ];
}
