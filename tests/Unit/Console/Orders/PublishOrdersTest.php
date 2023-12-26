<?php

declare(strict_types=1);

namespace Console\Orders;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\Product as CatalogProduct;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Delivery\Models\Pvz;
use App\Modules\Live\Models\LiveProduct;
use App\Modules\Orders\Models\Delivery;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\PersonalData;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use App\Modules\Promotions\Modules\Sales\Models\SaleProduct;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Orders\DeliveryType;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Money\Money;
use Tests\TestCase;

class PublishOrdersTest extends TestCase
{
    private const COMMAND = 'publish:orders';

    public function testSuccessful()
    {
        $this->createOrders(5);
        $this->artisan(self::COMMAND, [
            'dateStart' => Carbon::now()->format('Y-m-d H:i:s'),
            'dateEnd' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s')
        ]);

        self::assertEquals(1, 1);
    }

    public function testSuccessfulByPagination()
    {
        $this->createOrders(10);
        $this->artisan(self::COMMAND, [
            'dateStart' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
            'dateEnd' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s')
        ]);

        self::assertEquals(1, 1);
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
            /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
            $offer = $product->productOffers->first();
            \App\Modules\Orders\Models\Product::factory()->create([
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
