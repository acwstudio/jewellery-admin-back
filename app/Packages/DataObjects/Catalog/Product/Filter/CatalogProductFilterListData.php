<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product\Filter;

use Illuminate\Support\Collection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(schema: 'catalog_product_filter_list_data', type: 'object')]
class CatalogProductFilterListData extends Data
{
    public function __construct(
        #[Property(
            property: 'filters',
            type: 'array',
            items: new Items(ref: '#/components/schemas/catalog_product_filter_data'),
            nullable: true
        )]
        #[DataCollectionOf(CatalogProductFilterData::class)]
        public readonly DataCollection $filters
    ) {
    }

    public static function fromCollection(Collection $collection): self
    {
        return new self(
            CatalogProductFilterData::collection($collection->all())
        );
    }
}
