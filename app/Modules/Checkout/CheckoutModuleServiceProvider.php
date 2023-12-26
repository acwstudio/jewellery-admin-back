<?php

declare(strict_types=1);

namespace App\Modules\Checkout;

use App\Packages\ModuleClients\CheckoutModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class CheckoutModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        CheckoutModuleClientInterface::class => CheckoutModuleClient::class,
    ];
}
