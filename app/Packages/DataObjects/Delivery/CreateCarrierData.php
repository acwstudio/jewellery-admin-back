<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(schema: 'create_carrier_data', type: 'object')]
class CreateCarrierData
{
    public function __construct(
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'external_id', type: 'string')]
        public readonly string $external_id
    ) {
    }
}
