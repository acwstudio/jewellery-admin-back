<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'carrier_data', type: 'object')]
class CarrierData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'external_id', type: 'string')]
        public readonly string $external_id,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name
    ) {
    }
}
