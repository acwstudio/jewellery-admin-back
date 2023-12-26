<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\Collection;

use App\Modules\Collections\Models\Collection as CollectionModel;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'collections_collection_short_data', type: 'object')]
class CollectionShortData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'slug', type: 'string')]
        public readonly string $slug,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(
            property: 'products',
            type: 'array',
            items: new Items(type: 'integer')
        )]
        public readonly array $products
    ) {
    }

    public static function fromModel(CollectionModel $collection): self
    {
        return new self(
            id: $collection->id,
            slug: $collection->slug,
            name: $collection->name,
            products: $collection->products()->allRelatedIds()->all(),
        );
    }
}
