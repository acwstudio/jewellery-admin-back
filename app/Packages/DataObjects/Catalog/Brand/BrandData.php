<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Brand;

use App\Modules\Catalog\Models\Brand;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'brand_data',
    description: 'Brand data',
    type: 'object'
)]
class BrandData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name
    ) {
    }

    public static function fromModel(Brand $brand): self
    {
        return new self(
            $brand->id,
            $brand->name
        );
    }

    public static function customFromArray(array $brand): self
    {
        return new self(
            $brand['id'],
            $brand['name']
        );
    }
}
