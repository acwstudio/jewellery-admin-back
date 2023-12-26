<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Packages\DataObjects\Orders\Item\OrderItemData;
use App\Packages\DataObjects\Orders\Item\OrderItemListData;
use App\Packages\DataObjects\Users\Order\GetOrderListData;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\QueryParameter;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

class OrderController extends Controller
{
    public function __construct(
        protected readonly UsersModuleClientInterface $usersModuleClient
    ) {
    }

    #[Get(
        path: '/api/v1/user/order/{id}',
        summary: 'Получить заказ по ID',
        tags: ['User'],
        responses: [
            new Response(
                response: 200,
                description: 'Заказ',
                content: new JsonContent(ref: '#/components/schemas/orders_order_item_data')
            )
        ]
    )]
    public function get(int $id): OrderItemData
    {
        return $this->usersModuleClient->getOrder($id);
    }

    #[Get(
        path: '/api/v1/user/order',
        summary: 'Получить заказы',
        tags: ['User'],
        parameters: [
            new QueryParameter(
                name: 'pagination',
                description: 'Пагинация',
                required: false,
                schema: new Schema(ref: '#/components/schemas/pagination_data', type: 'object'),
                style: 'deepObject',
                explode: true
            )
        ],
        responses: [
            new Response(
                response: 200,
                description: 'Список заказов',
                content: new JsonContent(ref: '#/components/schemas/orders_order_item_list_data')
            )
        ]
    )]
    public function getList(GetOrderListData $data): OrderItemListData
    {
        return $this->usersModuleClient->getOrders($data);
    }
}
