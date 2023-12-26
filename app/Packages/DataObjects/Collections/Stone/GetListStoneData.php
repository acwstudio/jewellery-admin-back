<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Stone;

use App\Packages\DataObjects\Collections\Filter\FilterStoneData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'collections_get_list_stone_data',
    description: 'Получение списка вставок (камней) коллекции',
    type: 'object'
)]
class GetListStoneData extends Data
{
    public function __construct(
        #[Property(property: 'pagination', ref: '#/components/schemas/pagination_data', nullable: true)]
        public readonly ?PaginationData $pagination,
        #[Property(property: 'filter', ref: '#/components/schemas/collections_filter_stone_data', nullable: true)]
        public readonly ?FilterStoneData $filter
    ) {
    }
}
