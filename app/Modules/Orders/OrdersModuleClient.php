<?php

declare(strict_types=1);

namespace App\Modules\Orders;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\UseCase\CreateOrder;
use App\Modules\Orders\UseCase\GetOrder;
use App\Modules\Orders\UseCase\ImportOrderStatuses;
use App\Modules\Orders\UseCase\GetOrderItem;
use App\Modules\Orders\UseCase\PublishOrder;
use App\Packages\DataObjects\Orders\CreateOrder\CreateOrderData;
use App\Packages\DataObjects\Orders\Item\GetOrderItemListData;
use App\Packages\DataObjects\Orders\Item\OrderItemData;
use App\Packages\DataObjects\Orders\Item\OrderItemListData;
use App\Packages\DataObjects\Orders\Order\GetOrderListData;
use App\Packages\DataObjects\Orders\Order\OrderData;
use App\Packages\DataObjects\Orders\Order\OrderListData;
use App\Packages\DataObjects\Orders\Order\OrderWithPaymentData;
use App\Packages\ModuleClients\OrdersModuleClientInterface;
use Exception;
use Illuminate\Support\Facades\App;

class OrdersModuleClient implements OrdersModuleClientInterface
{
    /**
     * @throws Exception
     */
    public function getOrder(int $id): OrderWithPaymentData|OrderData
    {
        /** @var GetOrder $useCase */
        $useCase = App::make(GetOrder::class);
        return $useCase->getOrder($id);
    }

    public function getOrderItemByUserId(int $id, string $userId): ?OrderItemData
    {
        /** @var GetOrderItem $useCase */
        $useCase = App::make(GetOrderItem::class);
        return $useCase->getByUserId($id, $userId);
    }

    public function getOrders(GetOrderListData $data): OrderListData
    {
        /** @var GetOrder $useCase */
        $useCase = App::make(GetOrder::class);
        return $useCase->getOrders($data);
    }

    public function getOrderItems(GetOrderItemListData $data): OrderItemListData
    {
        /** @var GetOrderItem $useCase */
        $useCase = App::make(GetOrderItem::class);
        return $useCase->getList($data);
    }

    /**
     * @throws Exception
     */
    public function createOrder(CreateOrderData $data): OrderWithPaymentData|OrderData
    {
        /** @var Order $order */
        $order = App::call(CreateOrder::class, [
            'project'      => config('orders.project'),
            'country'      => config('orders.country'),
            'currency'     => config('orders.currency'),
            'paymentType' => $data->paymentType,
            'comment'      => $data->comment,
            'personalData' => $data->personalData,
            'deliveryData' => $data->delivery,
        ]);


        /** @var GetOrder $useCase */
        $useCase = App::make(GetOrder::class);
        return $useCase->getOrder($order->id);
    }

    public function publishOrder(int $id): void
    {
        App::call(PublishOrder::class, ['id' => $id]);
    }

    public function importOrderStatuses(?callable $onEach = null): void
    {
        App::call(ImportOrderStatuses::class, [$onEach]);
    }
}
