<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promocode;

use Money\Money;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

class CreatePromocodePrice extends Data
{
    public function __construct(
        #[MapOutputName('product_offer_id')]
        public readonly int $productOfferId,
        #[MapOutputName('shop_cart_token')]
        public readonly string $shopCartToken,
        public readonly Money $price
    ) {
    }
}
