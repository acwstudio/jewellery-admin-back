<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Price\Import;

use Money\Money;
use Spatie\LaravelData\Data;

class ImportProductOfferPriceSaleData extends Data
{
    public function __construct(
        public readonly string $external_id,
        public readonly string $sku,
        public readonly Money $money,
        public readonly ?string $size = null,
    ) {
    }
}
