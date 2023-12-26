<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Price;

use App\Packages\DataCasts\MoneyCast;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Money\Money;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class ImportProductOfferPriceData extends Data
{
    public function __construct(
        public readonly OfferPriceTypeEnum $type,
        #[WithCast(MoneyCast::class, isDecimal: true)]
        public readonly Money $money
    ) {
    }
}
