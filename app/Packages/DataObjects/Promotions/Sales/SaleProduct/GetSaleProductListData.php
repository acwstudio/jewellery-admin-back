<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Sales\SaleProduct;

use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Common\RequestMergeData;
use App\Packages\DataObjects\Promotions\Sales\Filter\FilterSaleProductData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'promotions_sales_get_sale_product_list_data',
    description: 'Получение списка акционных товаров',
    type: 'object'
)]
class GetSaleProductListData extends RequestMergeData
{
    public function __construct(
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination = null,
        #[Property(
            property: 'filter',
            ref: '#/components/schemas/promotions_sales_filter_sale_product_data',
            nullable: true
        )]
        public readonly ?FilterSaleProductData $filter = null
    ) {
        $this->merge();
    }
}
