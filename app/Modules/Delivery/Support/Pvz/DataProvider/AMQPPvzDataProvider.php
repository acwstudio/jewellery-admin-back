<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support\Pvz\DataProvider;

use App\Packages\ModuleClients\AMQPModuleClientInterface;

class AMQPPvzDataProvider implements PvzDataProviderInterface
{
    public function __construct(
        private readonly AMQPModuleClientInterface $AMQPModuleClient,
    ) {
    }

    public function import(\Closure $callback): void
    {
        $queue = config('import.queues.pvz');
        $this->AMQPModuleClient->consume($queue, $callback);
    }
}
