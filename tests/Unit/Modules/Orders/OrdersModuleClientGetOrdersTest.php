<?php

declare(strict_types=1);

namespace Modules\Orders;

use App\Modules\Catalog\Models\Product as CatalogProduct;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Delivery\Models\Pvz;
use App\Modules\Orders\Models\Delivery;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\PersonalData;
use App\Modules\Orders\Models\Product;
use App\Packages\DataObjects\Orders\Filter\BetweenDatetimeData;
use App\Packages\DataObjects\Orders\Filter\FilterOrderData;
use App\Packages\DataObjects\Orders\Order\GetOrderListData;
use App\Packages\DataObjects\Orders\Order\OrderData;
use App\Packages\DataObjects\Orders\Order\OrderListData;
use App\Packages\DataObjects\Orders\Order\OrderWithPaymentData;
use App\Packages\Enums\Orders\DeliveryType;
use App\Packages\ModuleClients\OrdersModuleClientInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class OrdersModuleClientGetOrdersTest extends TestCase
{
    private OrdersModuleClientInterface $moduleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleClient = app(OrdersModuleClientInterface::class);
    }

    public function testSuccessfulOne(): void
    {
        $orders = $this->createOrders(3);
        /** @var Order $order */
        $order = $orders->random();

        $result = $this->moduleClient->getOrder($order->getKey());
        self::assertInstanceOf(OrderData::class, $result);
    }

    public function testSuccessful(): void
    {
        $this->createOrders(3);

        $result = $this->moduleClient->getOrders(new GetOrderListData());
        self::assertInstanceOf(OrderListData::class, $result);
        self::assertCount(3, $result->items->all());
    }

    public function testSuccessfulByFilterBetweenDatetime(): void
    {
        $this->createOrders(5);

        $start = Carbon::now();
        $end = Carbon::now()->addDays(2);

        $result = $this->moduleClient->getOrders(new GetOrderListData(
            filter: new FilterOrderData(
                between_datetime: new BetweenDatetimeData($start, $end)
            )
        ));
        self::assertInstanceOf(OrderListData::class, $result);
        self::assertCount(2, $result->items->all());
    }

    private function createOrders(int $count = 1): Collection
    {
        $orders = collect();
        for ($i = 1; $i <= $count; $i++) {
            $orders->add(
                Order::factory()->create([
                    'created_at' => Carbon::now()->addDays($i)
                ])
            );
        }
        $products = CatalogProduct::factory(3)->create(['setFull' => true]);

        /** @var Order $order */
        foreach ($orders as $order) {
            $this->createOrderProducts($order, $products);
            $this->createDelivery($order);
            PersonalData::factory()->create(['order_id' => $order]);
        }

        return $orders;
    }

    private function createOrderProducts(Order $order, Collection $products): void
    {
        /** @var CatalogProduct $product */
        foreach ($products as $product) {
            /** @var ProductOffer $offer */
            $offer = $product->productOffers->first();
            Product::factory()->create([
                'product_offer_id' => $offer,
                'sku' => $product->sku,
                'size' => $offer->size,
                'order_id' => $order
            ]);
        }
    }

    private function createDelivery(Order $order): void
    {
        /** @var Pvz $pvz */
        $pvz = Pvz::factory()->create();

        Delivery::factory()->create([
            'order_id' => $order,
            'delivery_type' => DeliveryType::PVZ->value,
            'price' => $pvz->price,
            'pvz_id' => $pvz
        ]);
    }
}
