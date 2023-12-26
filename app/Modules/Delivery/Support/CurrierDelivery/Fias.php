<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Support\CurrierDelivery;

class Fias
{
    public function __construct(
        public readonly string $regionId,
        public readonly string $streetId,
        public readonly string $houseId
    ) {
    }
}
