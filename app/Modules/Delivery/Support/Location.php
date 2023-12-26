<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support;

class Location
{
    public function __construct(
        public readonly string $latitude,
        public readonly string $longitude
    ) {
    }
}
