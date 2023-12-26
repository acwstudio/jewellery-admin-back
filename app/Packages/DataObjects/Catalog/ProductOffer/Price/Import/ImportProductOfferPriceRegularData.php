<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Price\Import;

use Money\Money;
use Spatie\LaravelData\Data;

class ImportProductOfferPriceRegularData extends Data
{
    public function __construct(
        public readonly string $external_id,
        public readonly string $sku,
        public readonly Money $regularMoney,
        public readonly ?string $size = null,
        public readonly array $data = []
    ) {
    }
}
