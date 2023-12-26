<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Filter;

use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Data;

#[Schema(schema: 'catalog_filter_category_data', type: 'object')]
class FilterCategoryData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(property: 'has_product', nullable: true)]
        #[Nullable, BooleanType]
        public readonly ?bool $has_product = null,
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
