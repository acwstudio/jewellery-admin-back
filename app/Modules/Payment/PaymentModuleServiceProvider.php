<?php

declare(strict_types=1);

namespace App\Modules\Payment;

use App\Packages\ApiClients\Payment\ApiSberClient;
use App\Packages\ModuleClients\ApiSberClientInterface;
use App\Packages\ModuleClients\PaymentModuleClientInterface;
use Illuminate\Support\ServiceProvider;

class PaymentModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        PaymentModuleClientInterface::class => PaymentModuleClient::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerBindings();
    }

    /**
     * Регистрация биндингов
     */
    private function registerBindings(): void
    {
        $this->app->bind(ApiSberClientInterface::class, ApiSberClient::class);
    }
}
