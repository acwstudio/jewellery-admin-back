<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer;

use Spatie\LaravelData\Data;

class ImportProductOfferData extends Data
{
    public function __construct(
        public readonly ?string $size,
        public readonly ?string $weight,
    ) {
    }
}
