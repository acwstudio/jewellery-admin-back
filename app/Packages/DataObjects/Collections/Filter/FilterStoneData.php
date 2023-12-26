<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Filter;

use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

#[Schema(schema: 'collections_filter_stone_data', type: 'object')]
class FilterStoneData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(
            property: 'name',
            description: 'Название вставки (камня) коллекции',
            type: 'string',
            nullable: true
        )]
        #[Nullable, StringType]
        public readonly ?string $name
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
