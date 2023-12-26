<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ProductsGetStockResponseData extends Data
{
    public function __construct(
        #[MapInputName('Result')]
        public readonly bool $result,
        #[MapInputName('ErrorMessage')]
        public readonly string $errorMessage,
        #[MapInputName('Products')]
        #[DataCollectionOf(ProductStockData::class)]
        public readonly DataCollection $products
    ) {
    }
}
