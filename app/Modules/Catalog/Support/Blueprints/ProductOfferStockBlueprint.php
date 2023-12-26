<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Blueprints;

use App\Packages\Enums\Catalog\OfferStockReasonEnum;

class ProductOfferStockBlueprint
{
    public function __construct(
        public readonly int $count,
        public readonly OfferStockReasonEnum $reason
    ) {
    }
}
