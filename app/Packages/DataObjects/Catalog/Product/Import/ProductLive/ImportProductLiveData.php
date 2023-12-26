<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product\Import\ProductLive;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ImportProductLiveData extends Data
{
    public function __construct(
        public readonly Collection $products
    ) {
    }
}
