<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'update_carrier_data', type: 'object')]
class UpdateCarrierData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'external_id', type: 'string')]
        public readonly string $external_id
    ) {
    }
}
