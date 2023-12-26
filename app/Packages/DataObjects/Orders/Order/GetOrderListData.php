<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Order;

use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Common\RequestMergeData;
use App\Packages\DataObjects\Orders\Filter\FilterOrderData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(schema: 'orders_get_order_list_data', type: 'object')]
class GetOrderListData extends RequestMergeData
{
    public function __construct(
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination = null,
        #[Property(property: 'filter', ref: '#/components/schemas/orders_filter_order_data', nullable: true)]
        public readonly ?FilterOrderData $filter = null
    ) {
        $this->merge();
    }
}
