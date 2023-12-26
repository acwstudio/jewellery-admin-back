<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Collection;

use App\Packages\DataObjects\Collections\Filter\FilterCollectionData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'get_list_collection_data',
    description: 'Получение списка коллекции',
    type: 'object'
)]
class GetListCollectionData extends Data
{
    public function __construct(
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination,
        #[Property(property: 'filter', ref: '#/components/schemas/collections_filter_collection_data', nullable: true)]
        public readonly ?FilterCollectionData $filter
    ) {
    }
}
