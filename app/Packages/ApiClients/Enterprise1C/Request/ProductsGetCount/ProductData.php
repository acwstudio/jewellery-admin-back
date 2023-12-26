<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Enterprise1C\Request\ProductsGetCount;

use Spatie\LaravelData\Data;

class ProductData extends Data
{
    public function __construct(
        public readonly string $art,
        public readonly ?string $size,
    ) {
    }
}
