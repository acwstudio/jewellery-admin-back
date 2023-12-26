<?php

declare(strict_types=1);

namespace App\Modules\Orders\UseCase;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\Product;
use App\Modules\Orders\Services\OrderService;
use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Orders\Messages\CreateOrder\CreateOrderMessageAddressData;
use App\Packages\DataObjects\Orders\Messages\CreateOrder\CreateOrderMessageClientData;
use App\Packages\DataObjects\Orders\Messages\CreateOrder\CreateOrderMessageData;
use App\Packages\DataObjects\Orders\Messages\CreateOrder\CreateOrderMessageProductData;
use App\Packages\DataObjects\Orders\Messages\CreateOrder\CreateOrderMessageServiceData;
use App\Packages\DataObjects\Promotions\Sales\Filter\FilterSaleProductData;
use App\Packages\DataObjects\Promotions\Sales\SaleProduct\GetSaleProductListData;
use App\Packages\DataObjects\Promotions\Sales\SaleProduct\SaleProductData;
use App\Packages\Enums\Orders\DeliveryType;
use App\Packages\Exceptions\Orders\CreateOrderException;
use App\Packages\ModuleClients\AMQPModuleClientInterface;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;
use App\Packages\ModuleClients\PaymentModuleClientInterface;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class PublishOrder
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly DeliveryModuleClientInterface $deliveryModuleClient,
        private readonly AMQPModuleClientInterface $AMQPModuleClient,
        private readonly PromotionsModuleClientInterface $promotionsModuleClient,
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly PaymentModuleClientInterface $paymentModuleClient,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws CreateOrderException
     */
    public function __invoke(int $id)
    {
        try {
            $order = $this->orderService->get($id);
            $this->send($order);
        } catch (\Throwable $e) {
            $this->logger->alert(
                "[x] Failed publish Order #$id",
                ['exception' => $e]
            );

            throw new CreateOrderException();
        }

        $this->logger->info("[+] Published Order #$id");
    }

    private function send(Order $order): void
    {
        $message = $this->prepareBody($order);
        $queue = config('export.queues.orders');
        $this->AMQPModuleClient->publish($queue, $message);
    }

    private function prepareBody(Order $order): string
    {
        $address = $this->getAddress($order);
        $client = $this->getClient($order);
        $products = $this->getProducts($order);
        $service = $this->getService($order);
        $sberId = $this->getSberId($order);

        $data = new CreateOrderMessageData(
            id: (string)$order->id,
            project: $order->project,
            country: $order->country,
            paymentType: $order->payment_type->value,
            createdAt: $order->created_at,
            isEmployee: false,
            address: $address,
            client: $client,
            products: $products,
            service: $service,
            promotionExternalId: $order->promotion_external_id,
            sberId: $sberId
        );

        return $data->toJson();
    }

    private function getAddress(Order $order): CreateOrderMessageAddressData
    {
        return match ($order->delivery->delivery_type) {
            DeliveryType::CURRIER => $this->getCreateOrderMessageAddressDataByCurrier(
                $order->delivery->currier_delivery_id
            ),
            DeliveryType::PVZ => $this->getCreateOrderMessageAddressDataByPvz(
                $order->delivery->pvz_id
            )
        };
    }

    private function getCreateOrderMessageAddressDataByCurrier(string $id): CreateOrderMessageAddressData
    {
        $address = $this->deliveryModuleClient->getCurrierDelivery($id)->currierDeliveryAddress;

        return new CreateOrderMessageAddressData(
            $address->regionFiasId,
            $address->streetFiasId,
            $address->houseFiasId,
            strval($address->zipCode),
            $address->region,
            $address->settlement,
            $address->city,
            $address->street,
            $address->house,
            $address->flat,
            $address->block
        );
    }

    private function getCreateOrderMessageAddressDataByPvz(int $id): CreateOrderMessageAddressData
    {
        $pvz = $this->deliveryModuleClient->getPvzById($id);

        return new CreateOrderMessageAddressData(
            pvzId: $pvz->external_id
        );
    }

    private function getClient(Order $order): CreateOrderMessageClientData
    {
        return new CreateOrderMessageClientData(
            $order->personalData->phone,
            $order->personalData->email,
            $order->personalData->name,
            $order->personalData->surname,
            $order->personalData->patronymic
        );
    }

    private function getProducts(Order $order): Collection
    {
        $catalogProducts = $this->getCatalogProducts($order);
        $saleProducts = $this->getSaleProducts($catalogProducts);
        return $order->products->map(function (Product $product) use ($catalogProducts, $saleProducts) {
            $saleId = $this->getPromotionExternalId($product->sku, $catalogProducts, $saleProducts);

            return new CreateOrderMessageProductData(
                externalId: $product->guid,
                sku: $product->sku,
                price: $product->price,
                count: $product->count,
                conversionRate: config('orders.conversion_rate'),
                amount: $product->amount,
                discount: $product->discount,
                saleId: $saleId,
                size: $product->size
            );
        });
    }

    private function getService(Order $order): CreateOrderMessageServiceData
    {
        return match ($order->delivery->delivery_type) {
            DeliveryType::CURRIER => $this->getCreateOrderMessageServiceDataByCurrier(
                $order->delivery->currier_delivery_id
            ),
            DeliveryType::PVZ => $this->getCreateOrderMessageServiceDataByPvz(
                $order->delivery->pvz_id
            )
        };
    }

    private function getCreateOrderMessageServiceDataByCurrier(string $id): CreateOrderMessageServiceData
    {
        $delivery = $this->deliveryModuleClient->getCurrierDelivery($id);

        return new CreateOrderMessageServiceData(
            $delivery->carrierId,
            $delivery->price
        );
    }

    private function getCreateOrderMessageServiceDataByPvz(int $id): CreateOrderMessageServiceData
    {
        $pvz = $this->deliveryModuleClient->getPvzById($id);

        return new CreateOrderMessageServiceData(
            $pvz->carrier->external_id,
            $pvz->price
        );
    }

    private function getSaleProducts(Collection $catalogProducts): Collection
    {
        if ($catalogProducts->isEmpty()) {
            return collect();
        }

        $response = $this->promotionsModuleClient->getSaleProducts(
            new GetSaleProductListData(
                pagination: new PaginationData(
                    page: 1,
                    per_page: $catalogProducts->count()
                ),
                filter: new FilterSaleProductData(
                    is_active: true,
                    product_id: $catalogProducts->implode('id', ',')
                )
            )
        );

        /** @var Collection $saleProducts */
        $saleProducts = $response->items->toCollection();
        return $saleProducts;
    }

    private function getCatalogProducts(Order $order): Collection
    {
        return $this->catalogModuleClient->getProductDataCollectionBySkuList(
            $order->products->pluck('sku')->toArray()
        );
    }

    private function getPromotionExternalId(string $sku, Collection $catalogProducts, Collection $saleProducts): ?string
    {
        /** @var int|null $productId */
        $productId = $catalogProducts->where('sku', '=', $sku)->pluck('id')->first();
        if (null === $productId) {
            return null;
        }

        /** @var SaleProductData|null $saleProductData */
        $saleProductData = $saleProducts->where('product_id', '=', $productId)->first();
        return $saleProductData?->promotion_external_id;
    }

    private function getSberId(Order $order): ?string
    {
        if (PaymentTypeEnum::SBER_PAY != $order->payment_type) {
            return null;
        }

        try {
            $payment = $this->paymentModuleClient->getPayment($order->payment_id);
            return $payment->bankOrderId;
        } catch (\Throwable $e) {
            $this->logger->error('[PublishOrder] Error get SberID', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }
}
