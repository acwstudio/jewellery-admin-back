<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Blueprints;

use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Money\Money;

class ProductOfferPriceBlueprint
{
    public function __construct(
        public readonly Money $price,
        public readonly OfferPriceTypeEnum $type
    ) {
    }
}
