<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users\Order;

use App\Modules\Orders\Models\Order;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Users\UserTestCase;

class OrderControllerGetTest extends UserTestCase
{
    private const METHOD = '/api/v1/user/order/';

    public function testSuccessful()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);
        $order = $this->createOrder(['user_id' => $user]);

        $response = $this->get(self::METHOD . $order->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('date', $content);
        self::assertArrayHasKey('order_id', $content);
        self::assertArrayHasKey('delivery_type', $content);
        self::assertArrayHasKey('delivery_address', $content);
        self::assertArrayHasKey('full_price', $content);
        self::assertArrayHasKey('status', $content);
        self::assertArrayHasKey('products', $content);
        self::assertIsArray($content['products']);
        self::assertNotEmpty($content['products']);
        foreach ($content['products'] as $product) {
            self::assertArrayHasKey('image', $product);
            self::assertArrayHasKey('name', $product);
            self::assertArrayHasKey('size', $product);
            self::assertArrayHasKey('regular_price', $product);
            self::assertArrayHasKey('promo_price', $product);
            self::assertArrayHasKey('count', $product);
            self::assertArrayHasKey('slug', $product);
        }
        self::assertArrayHasKey('products_count', $content);
        self::assertArrayHasKey('discount_sale', $content);
        self::assertArrayHasKey('delivery_price', $content);
    }

    public function testFailure()
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->get(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureUser()
    {
        $response = $this->get(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function createOrder(array $params = []): Order
    {
        /** @var Order $order */
        $order = Order::factory()->create($params);
        $this->createOrderProducts($order, 3);
        $this->createOrderDelivery($order);

        return $order;
    }
}
