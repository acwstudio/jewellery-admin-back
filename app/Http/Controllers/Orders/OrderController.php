<?php

declare(strict_types=1);

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Orders\CreateOrder\CreateOrderData;
use App\Packages\DataObjects\Orders\Order\OrderData;
use App\Packages\DataObjects\Orders\Order\OrderWithPaymentData;
use App\Packages\ModuleClients\OrdersModuleClientInterface;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\PathParameter;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrdersModuleClientInterface $ordersModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/orders/order/{id}',
        summary: 'Получить заказ по ID',
        tags: ['Orders'],
        parameters: [
            new PathParameter(
                name: 'id',
                schema: new Schema(type: 'integer')
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Заказ',
                content: new JsonContent(oneOf: [
                    new Schema(ref: '#/components/schemas/orders_order_data'),
                    new Schema(ref: '#/components/schemas/orders_order_with_payment_data')
                ])
            )
        ]
    )]
    public function get(int $id): OrderWithPaymentData|OrderData
    {
        return $this->ordersModuleClient->getOrder($id);
    }

    #[Post(
        path: '/api/v1/orders/order',
        summary: 'Создать заказ',
        requestBody: new RequestBody(
            content: new JsonContent(ref: '#/components/schemas/create_order_data')
        ),
        tags: ['Orders'],
        responses: [
            new Response(
                response: 200,
                description: 'Созданный заказ',
                content: new JsonContent(oneOf: [
                    new Schema(ref: '#/components/schemas/orders_order_data'),
                    new Schema(ref: '#/components/schemas/orders_order_with_payment_data')
                ])
            )
        ],
    )]
    public function create(CreateOrderData $data): OrderWithPaymentData | OrderData
    {
        return $this->ordersModuleClient->createOrder($data);
    }
}
