<?php

declare(strict_types=1);

namespace App\Modules\ShopCart;

use App\Packages\ModuleClients\ShopCartModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class ShopCartModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        ShopCartModuleClientInterface::class => ShopCartModuleClient::class,
    ];
}
