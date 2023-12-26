<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support;

class Address
{
    public function __construct(
        public readonly string $area,
        public readonly string $city,
        public readonly string $district,
        public readonly string $street,
        public readonly string $address
    ) {
    }
}
