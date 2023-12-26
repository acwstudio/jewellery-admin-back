<?php

declare(strict_types=1);

namespace App\Modules\OpenSearch;

use App\Packages\ModuleClients\OpenSearchModuleClientInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use OpenSearch\Client;
use OpenSearch\ClientBuilder;

class OpenSearchModuleServiceProvider extends ServiceProvider
{
    public array $singletons = [
        OpenSearchModuleClientInterface::class => OpenSearchModuleClient::class,
    ];

    public function boot(): void
    {
        $this->registerOpenSearchClient();
    }

    private function registerOpenSearchClient(): void
    {
        $this->app->bind(Client::class, function (Application $app) {
            /** @var ClientBuilder $builder */
            $builder = $app->make(ClientBuilder::class);

            return $builder
                ->setHosts([config('opensearch.host')])
                ->setBasicAuthentication(
                    config('opensearch.auth.username'),
                    config('opensearch.auth.password')
                )
                ->build();
        });
    }
}
