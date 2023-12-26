<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\Events\OrderCreated;
use App\Packages\ModuleClients\OrdersModuleClientInterface;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;
use Psr\Log\LoggerInterface;

class ClearShopCart
{
    public function __construct(
        private readonly ShopCartModuleClientInterface $shopCartModuleClient,
        private readonly OrdersModuleClientInterface $ordersModuleClient,
        private readonly LoggerInterface $logger
    ) {
    }

    public function handle(OrderCreated $event): void
    {
        try {
            $order = $this->ordersModuleClient->getOrder($event->orderId);
            $this->shopCartModuleClient->clearShopCart($order->shopCartToken);
        } catch (\Throwable $e) {
            $this->logger->error('[ClearShopCartListener] Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'order_id' => $event->orderId
            ]);
        }
    }
}
