<?php

declare(strict_types=1);

namespace App\Modules\Storage;

use App\Packages\ModuleClients\StorageModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class StorageModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        StorageModuleClientInterface::class => StorageModuleClient::class,
    ];
}
