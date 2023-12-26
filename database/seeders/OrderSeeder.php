<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Catalog\Models\Product as CatalogProduct;
use App\Modules\Delivery\Models\Pvz;
use App\Modules\Orders\Models\Delivery;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\PersonalData;
use App\Packages\Enums\Orders\DeliveryType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $this->createOrders(10);
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
