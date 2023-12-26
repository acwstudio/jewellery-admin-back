<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class ProductStockData extends Data
{
    public function __construct(
        #[MapInputName('UID')]
        public readonly string $external_id,
        #[MapInputName('VendorCode')]
        public readonly string $sku,
        #[MapInputName('Size')]
        public readonly ?string $size,
        #[MapInputName('StockCount')]
        public readonly int $stockCount
    ) {
    }
}
