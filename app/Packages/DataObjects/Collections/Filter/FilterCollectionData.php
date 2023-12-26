<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Filter;

use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

#[Schema(schema: 'collections_filter_collection_data', type: 'object')]
class FilterCollectionData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(
            property: 'stone',
            description: 'Идентификатор вставки (камня) коллекции',
            type: 'integer',
            nullable: true
        )]
        #[Nullable, IntegerType]
        public readonly ?int $stone,
        #[Property(
            property: 'name',
            description: 'Поиск в наименование и описании коллекции',
            type: 'string',
            nullable: true
        )]
        #[Nullable, StringType]
        public readonly ?string $name
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
