<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product\Filter;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(schema: 'catalog_product_filter_context_data', type: 'object')]
class CatalogProductFilterContextData extends Data
{
    public function __construct(
        #[Property(property: 'min', type: 'integer', nullable: true)]
        public readonly ?int $min = null,
        #[Property(property: 'max', type: 'integer', nullable: true)]
        public readonly ?int $max = null,
        #[Property(
            property: 'options',
            type: 'array',
            items: new Items(ref: '#/components/schemas/catalog_product_option_value_data'),
            nullable: true
        )]
        #[DataCollectionOf(CatalogProductOptionValueData::class)]
        public readonly ?DataCollection $options = null
    ) {
    }
}
