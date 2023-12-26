<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

class CreateAddressData extends Data
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $address,
        public readonly string $postal_code,
        public readonly string $fias,
        public readonly string $region,
        public readonly string $city,
        public readonly string $street,
        public readonly string $house,
        public readonly string $flat
    ) {
    }
}
