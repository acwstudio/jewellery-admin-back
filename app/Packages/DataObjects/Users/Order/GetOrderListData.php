<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Users\Order;

use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\Enums\Orders\OrderSortColumnEnum;
use App\Packages\Enums\SortOrderEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'users_get_order_list_data',
    description: 'Получение списка заказов',
    type: 'object'
)]
class GetOrderListData extends Data
{
    public function __construct(
        #[Property(
            property: 'sort_by',
            ref: '#/components/schemas/orders_order_sort_column',
            type: 'string',
            nullable: true
        )]
        #[RequiredWith('sort_order')]
        public readonly ?OrderSortColumnEnum $sort_by = null,
        #[Property(property: 'sort_order', ref: '#/components/schemas/sort_order', type: 'string', nullable: true)]
        #[RequiredWith('sort_by')]
        public readonly ?SortOrderEnum $sort_order = null,
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination = null,
    ) {
    }
}
