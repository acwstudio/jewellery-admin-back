<?php

declare(strict_types=1);

namespace App\Modules\Users\UseCases;

use App\Modules\Users\Models\User;
use App\Packages\DataObjects\Orders\Filter\FilterOrderData;
use App\Packages\DataObjects\Orders\Item\GetOrderItemListData;
use App\Packages\DataObjects\Orders\Item\OrderItemData;
use App\Packages\DataObjects\Orders\Item\OrderItemListData;
use App\Packages\DataObjects\Users\Order\GetOrderListData;
use App\Packages\Enums\Orders\OrderSortColumnEnum;
use App\Packages\Enums\SortOrderEnum;
use App\Packages\ModuleClients\OrdersModuleClientInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetOrders
{
    public function __construct(
        private readonly OrdersModuleClientInterface $ordersModuleClient
    ) {
    }

    public function get(User $user, int $id): OrderItemData
    {
        $order = $this->ordersModuleClient->getOrderItemByUserId($id, $user->user_id);
        if (null === $order) {
            throw new NotFoundHttpException('Заказ не найден');
        }
        return $order;
    }

    public function getList(User $user, GetOrderListData $data): OrderItemListData
    {
        return $this->ordersModuleClient->getOrderItems(
            new GetOrderItemListData(
                sort_by: $data->sort_by ?? OrderSortColumnEnum::CREATED_AT,
                sort_order: $data->sort_order ?? SortOrderEnum::DESC,
                pagination: $data->pagination,
                filter: new FilterOrderData(
                    user_id: $user->user_id
                )
            )
        );
    }
}
