<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Live\LiveProduct;

use App\Modules\Live\Enums\LiveProductSortColumnEnum;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Common\RequestMergeData;
use App\Packages\DataObjects\Live\Filter\FilterLiveProductData;
use App\Packages\Enums\SortOrderEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;

#[Schema(
    schema: 'live_get_live_product_list_data',
    description: 'Получение коллекции продуктов Прямого эфира',
    type: 'object'
)]
class GetLiveProductListData extends RequestMergeData
{
    public function __construct(
        #[Property(
            property: 'sort_by',
            ref: '#/components/schemas/live_product_sort_column_enum',
            type: 'string',
            nullable: true
        )]
        #[RequiredWith('sort_order')]
        public readonly ?LiveProductSortColumnEnum $sort_by = null,
        #[Property(
            property: 'sort_order',
            ref: '#/components/schemas/sort_order',
            type: 'string',
            nullable: true
        )]
        #[RequiredWith('sort_by')]
        public readonly ?SortOrderEnum $sort_order = null,
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination = null,
        #[Property(property: 'filter', ref: '#/components/schemas/live_filter_live_product_data', nullable: true)]
        public readonly ?FilterLiveProductData $filter = null
    ) {
        $this->merge();
    }
}
