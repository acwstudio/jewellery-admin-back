<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product\Import\ProductLive;

use Money\Money;
use Spatie\LaravelData\Data;

class ProductLivePriceData extends Data
{
    public function __construct(
        public readonly ?string $size,
        public readonly Money $price
    ) {
    }
}
