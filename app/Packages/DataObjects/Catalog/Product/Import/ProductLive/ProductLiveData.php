<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product\Import\ProductLive;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ProductLiveData extends Data
{
    public function __construct(
        public readonly string $sku,
        public readonly int $number,
        public readonly Carbon $datetime,
        public readonly Collection $prices,
    ) {
    }
}
