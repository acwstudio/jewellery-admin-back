<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Sales\SaleProduct\Import;

use Money\Money;
use Spatie\LaravelData\Data;

class ImportSaleProductData extends Data
{
    public function __construct(
        public readonly int $product_id,
        public readonly string $product_sku,
        public readonly Money $money
    ) {
    }
}
