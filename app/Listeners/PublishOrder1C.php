<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\Events\OrderCreated;
use App\Packages\ModuleClients\OrdersModuleClientInterface;

class PublishOrder1C
{
    public function __construct(
        private readonly OrdersModuleClientInterface $ordersModuleClient
    ) {
    }

    public function handle(OrderCreated $event): void
    {
        $this->ordersModuleClient->publishOrder($event->orderId);
    }
}
