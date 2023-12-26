<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Orders;

use App\Modules\Orders\Models\Order;
use App\Packages\Enums\Orders\OrderStatusEnum;
use App\Packages\ModuleClients\OrdersModuleClientInterface;
use Tests\TestCase;

class OrdersModuleClientImportOrderStatusesTest extends TestCase
{
    private OrdersModuleClientInterface $moduleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->moduleClient = app(OrdersModuleClientInterface::class);
    }

    public function testSuccessfulImportOrderStatuses()
    {
        /** @var Order $order */
        $order = Order::factory()->create();

        self::assertEmpty($order->external_id);
        self::assertEquals(OrderStatusEnum::CREATED, $order->status);

        $message = json_decode(
            file_get_contents($this->getTestResources('Orders_Status_1C-Site.json')),
            true
        );

        $message['order_id'] = $order->getKey();
        $this->mockAMQPModuleClient($message);
        $this->moduleClient->importOrderStatuses();

        $order->refresh();

        self::assertNotEmpty($order->external_id);
        self::assertEquals(OrderStatusEnum::PAID, $order->status);
        self::assertEquals('2023-12-06T13:38:03', $order->status_date->format('Y-m-d\TH:i:s'));
    }
}
