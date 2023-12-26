<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Feature;

use App\Packages\DataObjects\Catalog\Filter\FilterFeatureData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_get_list_feature_data',
    description: 'Получение списка свойств',
    type: 'object'
)]
class GetListFeatureData extends Data
{
    public function __construct(
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination,
        #[Property(property: 'filter', ref: '#/components/schemas/catalog_filter_feature_data', nullable: true)]
        public readonly ?FilterFeatureData $filter
    ) {
    }
}
