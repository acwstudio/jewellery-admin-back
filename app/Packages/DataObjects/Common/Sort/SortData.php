<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Common\Sort;

use App\Packages\Enums\SortOrderEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'sort_data',
    description: 'Сортировка',
    type: 'object'
)]
class SortData extends Data
{
    public function __construct(
        #[Property(property: 'sort_by', description: 'Поле сортировки', type: 'string', example: 'title')]
        public readonly string $sort_by,
        #[Property(property: 'sort_order', nullable: true)]
        public readonly SortOrderEnum $sort_order
    ) {
    }
}
