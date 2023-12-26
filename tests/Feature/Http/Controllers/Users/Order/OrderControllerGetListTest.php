<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Users\Order;

use App\Modules\Orders\Models\Order;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Users\UserTestCase;

class OrderControllerGetListTest extends UserTestCase
{
    private const METHOD = '/api/v1/user/order';

    public function testSuccessful()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        $this->createOrders(3, ['user_id' => $user]);
        $this->createOrders(2, ['user_id' => $this->getUser()]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    public function testSuccessfulEmpty()
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertEmpty($content['items']);
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function createOrders(int $count = 1, array $params = []): void
    {
        $orders = Order::factory($count)->create($params);
        /** @var Order $order */
        foreach ($orders as $order) {
            $this->createOrderProducts($order);
            $this->createOrderDelivery($order);
        }
    }
}
