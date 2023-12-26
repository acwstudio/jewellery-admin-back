<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions;

use App\Packages\DataCasts\MoneyCast;
use Money\Money;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class CreatePromotionBenefitProduct extends Data
{
    public function __construct(
        #[MapInputName('UID')]
        #[MapOutputName('external_id')]
        public readonly string $externalId,
        #[MapInputName('vendorCode')]
        public readonly string $sku,
        #[WithCast(MoneyCast::class, isDecimal: true)]
        public readonly Money $price,
        public readonly ?string $size = null
    ) {
    }
}
