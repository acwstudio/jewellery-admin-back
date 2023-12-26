<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Blueprints;

use App\Packages\Enums\Catalog\OfferReservationStatusEnum;

class ProductOfferReservationBlueprint
{
    public function __construct(
        public readonly int $count,
        public readonly OfferReservationStatusEnum $status
    ) {
    }
}
