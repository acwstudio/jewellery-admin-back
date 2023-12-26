<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\Orders\CreateOrder\CreateOrderData;
use App\Packages\DataObjects\Orders\Item\GetOrderItemListData;
use App\Packages\DataObjects\Orders\Item\OrderItemData;
use App\Packages\DataObjects\Orders\Item\OrderItemListData;
use App\Packages\DataObjects\Orders\Order\GetOrderListData;
use App\Packages\DataObjects\Orders\Order\OrderData;
use App\Packages\DataObjects\Orders\Order\OrderListData;
use App\Packages\DataObjects\Orders\Order\OrderWithPaymentData;

interface OrdersModuleClientInterface
{
    public function getOrder(int $id): OrderWithPaymentData|OrderData;
    public function getOrderItemByUserId(int $id, string $userId): ?OrderItemData;
    public function getOrders(GetOrderListData $data): OrderListData;
    public function getOrderItems(GetOrderItemListData $data): OrderItemListData;
    public function createOrder(CreateOrderData $data): OrderWithPaymentData|OrderData;
    public function publishOrder(int $id): void;

    public function importOrderStatuses(?callable $onEach = null): void;
}
