<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\CreateOrder;

use Money\Money;
use Spatie\LaravelData\Data;

class CreateOrderProductData extends Data
{
    public function __construct(
        public readonly int $productOfferId,
        public readonly string $guid,
        public readonly string $sku,
        public readonly int $count,
        public readonly Money $price,
        public readonly Money $amount,
        public readonly ?Money $discount = null,
        public readonly ?string $size = null
    ) {
    }
}
