<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Sales\CatalogProduct;

use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Common\RequestMergeData;
use App\Packages\Enums\Catalog\ProductSortColumnEnum;
use App\Packages\Enums\SortOrderEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;

#[Schema(
    schema: 'promotions_sales_get_catalog_product_list_data',
    description: 'Получить список товаров из Акции',
    required: ['items'],
    type: 'object'
)]
class GetCatalogProductListData extends RequestMergeData
{
    public function __construct(
        #[Property(property: 'sale_id', type: 'string', nullable: true)]
        public readonly ?string $sale_id = null,
        #[Property(property: 'sale_slug', type: 'string', nullable: true)]
        public readonly ?string $sale_slug = null,
        #[Property(property: 'is_active', type: 'boolean', nullable: true)]
        public readonly ?bool $is_active = null,
        #[Property(
            property: 'sort_by',
            ref: '#/components/schemas/catalog_product_sort_column',
            type: 'string',
            nullable: true
        )]
        #[RequiredWith('sort_order')]
        public readonly ?ProductSortColumnEnum $sort_by = null,
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
        #[Property(property: 'filter', ref: '#/components/schemas/catalog_filter_product_data', nullable: true)]
        public readonly ?FilterProductData $filter = null
    ) {
        $this->merge();
    }
}
