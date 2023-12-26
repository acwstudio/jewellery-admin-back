<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Enterprise1C\Request\ProductsGetCount;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ProductsGetStockRequestData extends Data
{
    public function __construct(
        #[DataCollectionOf(ProductData::class)]
        public readonly DataCollection $products
    ) {
    }
}
