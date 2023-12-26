<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Delivery\Models\CurrierDelivery;
use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use App\Modules\Orders\Models\Delivery;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\Product as OrderProduct;
use App\Modules\Users\Models\WishlistProduct;
use App\Modules\Users\UseCases\GetOrders;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Orders\Order\OrderData;
use App\Packages\DataObjects\Orders\Order\OrderDeliveryData;
use App\Packages\DataObjects\Orders\Order\OrderPersonalData;
use App\Packages\DataObjects\Orders\Order\OrderListData;
use App\Packages\Enums\Orders\DeliveryType;
use App\Packages\Support\PhoneNumber;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Money\Money;
use Tests\TestCase;

abstract class UserTestCase extends TestCase
{
    public function mockGetOrders(int $count = 1): void
    {
        $this->mock(GetOrders::class, function (MockInterface $mock) use ($count) {
            $items = [];
            $phoneNumber = new PhoneNumber();
            for ($i = 0; $i < $count; $i++) {
                $deliveryData = new OrderDeliveryData(DeliveryType::CURRIER);
                $personalData = new OrderPersonalData($phoneNumber, 'test@email.ru', 'Пользователь', null, null);
                $items[] = new OrderData($i + 1, Money::RUB(1000 * 100), $deliveryData, $personalData);
            }

            $collection = OrderData::collection($items);
            $orderListData = new OrderListData(
                $collection,
                new PaginationData(1, 15, $collection->count())
            );
            $mock->shouldReceive('__invoke')->andReturn($orderListData);
        });
    }

    public function createWishlistProducts(int $count = 1, array $params = []): Collection
    {
        $wishlistProducts = [];

        /** @var Collection<Product> $products */
        $products = Product::factory($count)->create(['setFull' => true]);
        foreach ($products as $product) {
            $params['product_id'] = $product;
            $wishlistProducts[] = WishlistProduct::factory()->create($params);
        }

        return new Collection($wishlistProducts);
    }

    public function createOrderProducts(Order $order, int $count = 1, array $params = []): void
    {
        for ($i = 1; $i <= $count; $i++) {
            /** @var Product $product */
            $product = Product::factory()->create(['setFull' => true]);
            /** @var ProductOffer $offer */
            $offer = $product->productOffers->first();
            $default = [
                'order_id' => $order->getKey(),
                'product_offer_id' => $offer->getKey(),
                'sku' => $product->sku,
                'size' => $offer->size
            ];
            $data = array_merge($default, $params);
            OrderProduct::factory()->create($data);
        }
        $order->refresh();
    }

    public function createOrderDelivery(Order $order): void
    {
        /** @var CurrierDelivery $currierDelivery */
        $currierDelivery = CurrierDelivery::factory()->create([
            'currier_delivery_address_id' => CurrierDeliveryAddress::factory()->create([
                'user_id' => $order->user_id,
            ]),
        ]);

        Delivery::factory()->create([
            'order_id' => $order,
            'delivery_type' => DeliveryType::CURRIER->value,
            'currier_delivery_id' => $currierDelivery
        ]);

        $order->refresh();
    }
}
