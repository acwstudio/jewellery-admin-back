<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Brand;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

#[Schema(
    schema: 'update_brand_data',
    description: 'Brand data',
    type: 'object'
)]
class UpdateBrandData extends Data
{
    public function __construct(
        #[Property(property: 'name', type: 'string')]
        #[Required,
    StringType]
        public readonly string $name
    ) {
    }
}
