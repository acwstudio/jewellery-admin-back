<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Stock;

use App\Packages\Enums\Catalog\OfferStockReasonEnum;
use Spatie\LaravelData\Data;

class ImportProductOfferStockData extends Data
{
    public function __construct(
        public readonly string $external_id,
        public readonly string $sku,
        public readonly ?string $size,
        public readonly int $count,
        public readonly OfferStockReasonEnum $reason
    ) {
    }
}
