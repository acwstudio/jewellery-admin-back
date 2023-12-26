<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product\Filter;

use App\Packages\Enums\FilterTypeEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'catalog_product_filter_data', type: 'object')]
class CatalogProductFilterData extends Data
{
    public function __construct(
        #[Property(property: 'position', type: 'int')]
        public readonly int $position,
        #[Property(property: 'title', type: 'string')]
        public readonly string $title,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'type')]
        public readonly FilterTypeEnum $type,
        #[Property(
            property: 'settings',
            ref: '#/components/schemas/catalog_product_filter_context_data',
            type: 'object'
        )]
        public readonly CatalogProductFilterContextData $settings
    ) {
    }
}
