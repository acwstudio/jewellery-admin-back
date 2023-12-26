<?php

declare(strict_types=1);

namespace App\Modules\AMQP;

use App\Packages\ModuleClients\AMQPModuleClientInterface;
use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPConnectionConfig;
use PhpAmqpLib\Connection\AMQPConnectionFactory;

class AMQPModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        AMQPModuleClientInterface::class => AMQPModuleClient::class,
    ];

    public function boot(): void
    {
        $this->registerConnection();
    }

    private function registerConnection(): void
    {
        $this->app->bind(AbstractConnection::class, function () {
            return AMQPConnectionFactory::create(
                $this->getConfig()
            );
        });
    }

    private function getConfig(): AMQPConnectionConfig
    {
        $config = new AMQPConnectionConfig();

        $config->setHost(config('amqp.connection.host'));
        $config->setPort(intval(config('amqp.connection.port')));
        $config->setUser(config('amqp.connection.user'));
        $config->setPassword(config('amqp.connection.pass'));
        $config->setVhost(config('amqp.connection.vhost'));
        $config->setIsSecure(config('amqp.connection.tls'));

        return $config;
    }
}
