<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Packages\DataObjects\Promotions\Filter\FilterPromocodeUsageData;
use App\Packages\DataObjects\Promotions\Promocode\PromocodeUsageData;
use App\Packages\DataObjects\Promotions\Promocode\SetPromocodeUsageOrderId;
use App\Packages\Events\OrderCreated;
use App\Packages\ModuleClients\OrdersModuleClientInterface;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class UpdatePromocodeUsage
{
    public function __construct(
        private readonly OrdersModuleClientInterface $ordersModuleClient,
        private readonly PromotionsModuleClientInterface $promotionsModuleClient,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function handle(OrderCreated $event): void
    {
        try {
            $orderData = $this->ordersModuleClient->getOrder($event->orderId);

            if (null === $orderData->promocode) {
                return;
            }

            $promocodeUsages = $this->promotionsModuleClient->getPromocodeUsages(
                new FilterPromocodeUsageData(
                    promotion_benefit_id: $orderData->promocode->id,
                    shop_cart_token: $orderData->shopCartToken
                )
            );

            /** @var PromocodeUsageData $promocodeUsageData */
            foreach ($promocodeUsages as $promocodeUsageData) {
                $this->updatePromocodeUsage($promocodeUsageData, $orderData->id);
            }
        } catch (Throwable $e) {
            $this->logger->error('[UpdatePromocodeUsageListener] Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'order_id' => $event->orderId
            ]);
        }
    }

    private function updatePromocodeUsage(PromocodeUsageData $promocodeUsageData, int $orderId): void
    {
        if (null !== $promocodeUsageData->order_id) {
            return;
        }

        try {
            $this->promotionsModuleClient->setPromocodeUsageOrderId(
                new SetPromocodeUsageOrderId($promocodeUsageData->id, $orderId)
            );
        } catch (Throwable $exception) {
            $this->logger->error('[UpdatePromocodeUsageListener] Error set orderId', [
                'message' => $exception->getMessage(),
                'order_id' => $orderId,
                'promocode_usage_id' => $promocodeUsageData->id
            ]);
        }
    }
}
