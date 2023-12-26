<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product\Filter;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'catalog_product_option_value_data', type: 'object')]
class CatalogProductOptionValueData extends Data
{
    public function __construct(
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'value', type: 'string')]
        public readonly string $value,
        #[Property(property: 'slug', type: 'string')]
        public readonly string $slug,
        #[Property(property: 'count', type: 'int', nullable: true)]
        public readonly ?int $count = null,
        #[Property(property: 'context', type: 'array', items: new Items(type: 'string'), nullable: true)]
        public readonly ?array $context = null
    ) {
    }
}
