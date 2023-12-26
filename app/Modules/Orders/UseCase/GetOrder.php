<?php

declare(strict_types=1);

namespace App\Modules\Orders\UseCase;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Services\OrderService;
use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Orders\Order\GetOrderListData;
use App\Packages\DataObjects\Orders\Order\OrderData;
use App\Packages\DataObjects\Orders\Order\OrderDeliveryData;
use App\Packages\DataObjects\Orders\Order\OrderDeliveryPvzData;
use App\Packages\DataObjects\Orders\Order\OrderListData;
use App\Packages\DataObjects\Orders\Order\OrderPersonalData;
use App\Packages\DataObjects\Orders\Order\OrderWithPaymentData;
use App\Packages\DataObjects\Promotions\Promocode\PromocodeData;
use App\Packages\Enums\Orders\DeliveryType;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Exception;

class GetOrder
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly DeliveryModuleClientInterface $deliveryModuleClient,
        private readonly PromotionsModuleClientInterface $promotionsModuleClient,
    ) {
    }

    /**
     * @throws Exception
     */
    public function getOrder(int $id): OrderWithPaymentData|OrderData
    {
        $order = $this->orderService->get($id);

        return match ($order->payment_type ?? PaymentTypeEnum::CASH) {
            PaymentTypeEnum::CASH => new OrderData(
                id: $order->id,
                summary: $order->summary,
                delivery: $this->getDelivery($order),
                personalData: $this->getPersonalData($order),
                promocode: $this->getPromocode($order),
                shopCartToken: $order->shop_cart_token,
            ),
            PaymentTypeEnum::SBER_PAY => new OrderWithPaymentData(
                id: $order->id,
                summary: $order->summary,
                delivery: $this->getDelivery($order),
                personalData: $this->getPersonalData($order),
                paymentUrl: $this->getPaymentUrl($order),
                promocode: $this->getPromocode($order),
                shopCartToken: $order->shop_cart_token,
            ),
            PaymentTypeEnum::APPLE_PAY,
            PaymentTypeEnum::SAMSUNG_PAY,
            PaymentTypeEnum::GOOGLE_PAY => throw new Exception('To be implemented'),
        };
    }

    public function getOrders(GetOrderListData $data): OrderListData
    {
        $paginator = $this->orderService->all($data->pagination);

        $items = array_map(
            fn(Order $order) => new OrderData(
                id: $order->id,
                summary: $order->summary,
                delivery: $this->getDelivery($order),
                personalData: $this->getPersonalData($order),
                promocode: $this->getPromocode($order),
                shopCartToken: $order->shop_cart_token,
            ),
            $paginator->items(),
        );

        return new OrderListData(
            OrderData::collection($items),
            new PaginationData(
                $paginator->currentPage(),
                $paginator->perPage(),
                $paginator->total(),
                $paginator->lastPage(),
            ),
        );
    }

    private function getDelivery(Order $order): OrderDeliveryData
    {
        return match ($order->delivery->delivery_type) {
            DeliveryType::CURRIER => $this->getOrderDeliveryDataByCurrierId($order->delivery->currier_delivery_id),
            DeliveryType::PVZ => $this->getOrderDeliveryDataByPvzId($order->delivery->pvz_id),
        };
    }

    private function getOrderDeliveryDataByCurrierId(string $id): OrderDeliveryData
    {
        $delivery = $this->deliveryModuleClient->getCurrierDelivery($id);

        return new OrderDeliveryData(
            DeliveryType::CURRIER,
            $delivery->address,
        );
    }

    private function getOrderDeliveryDataByPvzId(int $id): OrderDeliveryData
    {
        $pvz = $this->deliveryModuleClient->getPvzById($id);

        return new OrderDeliveryData(
            DeliveryType::PVZ,
            pvz: new OrderDeliveryPvzData(
                $pvz->address,
                $pvz->work_time,
            ),
        );
    }

    private function getPersonalData(Order $order): OrderPersonalData
    {
        return new OrderPersonalData(
            $order->personalData->phone,
            $order->personalData->email,
            $order->personalData->name,
            $order->personalData->surname,
            $order->personalData->patronymic,
        );
    }

    private function getPromocode(Order $order): ?PromocodeData
    {
        if (null === $order->promotion_external_id) {
            return null;
        }

        return $this->promotionsModuleClient->getPromocodeByPromotionExternalId($order->promotion_external_id);
    }

    private function getPaymentUrl(Order $order): string
    {
        return $order->payment_url;
    }
}
