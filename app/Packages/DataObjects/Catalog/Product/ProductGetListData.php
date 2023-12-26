<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product;

use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Common\RequestMergeData;
use App\Packages\Enums\Catalog\ProductSortColumnEnum;
use App\Packages\Enums\SortOrderEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;

#[Schema(
    schema: 'product_get_list_data',
    description: 'Получение коллекции продуктов',
    type: 'object'
)]
class ProductGetListData extends RequestMergeData
{
    public function __construct(
        #[Property(
            property: 'sort_by',
            ref: '#/components/schemas/catalog_product_sort_column',
            type: 'string',
            nullable: true
        )]
        #[RequiredWith('sort_order')]
        public readonly ?ProductSortColumnEnum $sort_by = null,
        #[Property(property: 'sort_order', ref: '#/components/schemas/sort_order', type: 'string', nullable: true)]
        #[RequiredWith('sort_by')]
        public readonly ?SortOrderEnum $sort_order = null,
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination = null,
        #[Property(property: 'filter', ref: '#/components/schemas/catalog_filter_product_data', nullable: true)]
        public readonly ?FilterProductData $filter = null,
        #[Property(property: 'seo_url', type: 'string', nullable: true)]
        public readonly ?string $seo_url = null,
        #[Property(property: 'is_full', type: 'boolean', nullable: true)]
        public readonly ?bool $is_full = null
    ) {
        $this->merge();
    }
}
