<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Price;

use Carbon\Carbon;
use Money\Money;
use Spatie\LaravelData\Data;

class ImportProductOfferPriceLiveData extends Data
{
    public function __construct(
        public readonly string $external_id,
        public readonly string $sku,
        public readonly Carbon $date,
        public readonly Money $money,
        public readonly ?string $size = null,
        public readonly array $data = []
    ) {
    }
}
