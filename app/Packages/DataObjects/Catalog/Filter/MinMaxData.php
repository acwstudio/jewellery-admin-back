<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Filter;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\GreaterThanOrEqualTo;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

#[Schema(
    schema: 'catalog_filter_min_max_data',
    required: ['min', 'max'],
    type: 'object'
)]
class MinMaxData extends Data
{
    public function __construct(
        #[Property(property: 'min', type: 'integer')]
        #[IntegerType, Min(0), Required]
        public readonly int $min,
        #[Property(property: 'max', type: 'integer')]
        #[IntegerType, Min(0), Required, GreaterThanOrEqualTo('min')]
        public readonly int $max
    ) {
    }
}
