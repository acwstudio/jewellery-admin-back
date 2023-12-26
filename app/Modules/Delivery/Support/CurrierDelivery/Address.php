<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support\CurrierDelivery;

class Address
{
    public function __construct(
        public readonly int $zipCode,
        public readonly string $region,
        public readonly string $city,
        public readonly string $street,
        public readonly string $house,
        public readonly ?string $settlement = null,
        public readonly ?string $flat = null,
        public readonly ?string $block = null
    ) {
    }
}
