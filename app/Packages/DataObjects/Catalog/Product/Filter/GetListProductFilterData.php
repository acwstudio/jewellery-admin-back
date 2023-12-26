<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product\Filter;

use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Common\RequestMergeData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'catalog_get_list_product_filter_data',
    description: 'Получение фильтров продуктов',
    type: 'object'
)]
class GetListProductFilterData extends RequestMergeData
{
    public function __construct(
        #[Property(property: 'applied_filter', ref: '#/components/schemas/catalog_filter_product_data', nullable: true)]
        public readonly ?FilterProductData $applied_filter = null,
    ) {
        $this->merge();
    }
}
