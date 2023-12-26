<?php

declare(strict_types=1);

namespace App\Modules\Orders\UseCase;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\Product;
use App\Modules\Orders\Services\OrderService;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductItemData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Orders\Item\GetOrderItemListData;
use App\Packages\DataObjects\Orders\Item\OrderItemData;
use App\Packages\DataObjects\Orders\Item\OrderItemListData;
use App\Packages\DataObjects\Orders\Product\OrderProductData;
use App\Packages\Enums\Orders\DeliveryType;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use App\Packages\ModuleClients\DeliveryModuleClientInterface;
use Illuminate\Support\Collection;
use Money\Money;
use Spatie\LaravelData\DataCollection;

class GetOrderItem
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly DeliveryModuleClientInterface $deliveryModuleClient,
        private readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    public function get(int $id): OrderItemData
    {
        $order = $this->orderService->get($id);
        $catalogProducts = $this->getCatalogProducts(collect([$order]));
        return $this->createOrderItemData($order, $catalogProducts);
    }

    public function getByUserId(int $id, string $userId): ?OrderItemData
    {
        $order = $this->orderService->getByUserId($id, $userId);
        $catalogProducts = $this->getCatalogProducts(collect([$order]));
        if (null === $order) {
            return null;
        }
        return $this->createOrderItemData($order, $catalogProducts);
    }

    public function getList(GetOrderItemListData $data): OrderItemListData
    {
        $paginator = $this->orderService->all($data->pagination);
        $orders = collect($paginator->items());
        $catalogProducts = $this->getCatalogProducts($orders);

        $items = $orders->map(
            fn (Order $order) => $this->createOrderItemData($order, $catalogProducts)
        );

        return new OrderItemListData(
            OrderItemData::collection($items),
            new PaginationData(
                $paginator->currentPage(),
                $paginator->perPage(),
                $paginator->total(),
                $paginator->lastPage(),
            ),
        );
    }

    private function createOrderItemData(Order $order, Collection $catalogProducts): OrderItemData
    {
        $deliveryAddress = $this->getDeliveryAddress($order);
        $products = $this->getProductDataCollection($order, $catalogProducts);
        $productCount = $this->getProductCount($order);
        $discountSale = $this->getDiscountSale($order);

        return new OrderItemData(
            date: $order->created_at,
            order_id: $order->id,
            delivery_type: $order->delivery->delivery_type,
            delivery_address: $deliveryAddress,
            full_price: $order->summary,
            status: $order->status,
            products: $products,
            products_count: $productCount,
            discount_sale: $discountSale,
            delivery_price: $order->delivery->price
        );
    }

    private function createOrderProductData(Product $product, Collection $catalogProducts): OrderProductData
    {
        /** @var ProductItemData $productItemData */
        $productItemData = $catalogProducts->where('sku', '=', $product->sku)->first();
        $promoPrice = null;
        if (null !== $product->discount && (int)$product->discount->getAmount() > 0) {
            $promoPrice = $product->price->subtract($product->discount);
        }

        return new OrderProductData(
            image: $productItemData->image,
            name: $productItemData->name,
            size: $product->size,
            regular_price: $product->price,
            promo_price: $promoPrice,
            count: $product->count,
            slug: $productItemData->slug
        );
    }

    private function getDeliveryAddress(Order $order): string
    {
        return match ($order->delivery->delivery_type) {
            DeliveryType::CURRIER => $this->getDeliveryAddressByCurrierId($order->delivery->currier_delivery_id),
            DeliveryType::PVZ => $this->getDeliveryAddressByPvzId($order->delivery->pvz_id),
        };
    }

    private function getDeliveryAddressByCurrierId(string $id): string
    {
        $delivery = $this->deliveryModuleClient->getCurrierDelivery($id);
        return $delivery->address;
    }

    private function getDeliveryAddressByPvzId(int $id): string
    {
        $pvz = $this->deliveryModuleClient->getPvzById($id);
        return $pvz->address;
    }

    private function getProductDataCollection(Order $order, Collection $catalogProducts): DataCollection
    {
        $items = $order->products->map(
            fn (Product $product) => $this->createOrderProductData($product, $catalogProducts)
        );
        return OrderProductData::collection($items);
    }

    private function getProductCount(Order $order): int
    {
        $count = 0;
        foreach ($order->products as $product) {
            $count += $product->count;
        }
        return $count;
    }

    private function getDiscountSale(Order $order): Money
    {
        return $order->products->reduce(function (Money $carry, Product $product) {
            if (null === $product->discount) {
                return $carry;
            }
            return $carry->add($product->discount);
        }, Money::RUB(0));
    }

    private function getCatalogProducts(Collection $orders): Collection
    {
        $skuList = $this->getOrderProductSkuList($orders);
        /** @var Collection $response */
        $response = $this->catalogModuleClient->getScoutProducts(
            new ProductGetListData(
                pagination: new PaginationData(1, $skuList->count()),
                filter: new FilterProductData(
                    sku: $skuList->implode('sku', ',')
                )
            )
        )->items->toCollection();

        return $response;
    }

    private function getOrderProductSkuList(Collection $orders): Collection
    {
        $collection = new Collection();
        /** @var Order $order */
        foreach ($orders as $order) {
            $skuList = $order->products->all();
            $collection->push(...$skuList);
        }

        return $collection->unique('sku');
    }
}
