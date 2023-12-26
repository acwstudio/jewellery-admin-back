<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Stores;

use App\Modules\Catalog\Models\Brand;
use App\Modules\Stores\Models\StoreType;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'store_type_data',
    description: 'Store type data',
    type: 'object'
)]
class StoreTypeData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name
    )
    {
    }

    public static function fromModel(StoreType $storeType)
    {
        return new self(
            $storeType->id,
            $storeType->name
        );
    }
}
