<?php

declare(strict_types=1);

namespace App\Modules\Messages;

use App\Modules\Messages\MessageGateway\MessageGateway;
use App\Packages\ApiClients\Rapporto\RapportoApiClient;
use App\Packages\ModuleClients\MessageModuleClientInterface;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class MessagesModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        MessageModuleClientInterface::class => MessageModuleClient::class,
    ];

    public function boot(): void
    {
        $this->registerMessageGateway();
        $this->registerRapportoApiClient();
    }

    private function registerMessageGateway(): void
    {
        $this->app->singleton(MessageGateway::class, config('messages.message_gateway'));
    }

    private function registerRapportoApiClient(): void
    {
        $this->app->bind(RapportoApiClient::class, function () {
            return new RapportoApiClient(
                $this->app->make(LoggerInterface::class),
                config('messages.rapporto.login'),
                config('messages.rapporto.password'),
                config('messages.rapporto.uri'),
                config('messages.rapporto.service_number')
            );
        });
    }
}
