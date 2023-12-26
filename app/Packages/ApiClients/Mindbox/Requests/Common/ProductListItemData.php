<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Mindbox\Requests\Common;

use Spatie\LaravelData\Data;

class ProductListItemData extends Data
{
    public function __construct(
        public readonly ProductData $productGroup,
        public readonly int $count,
        public readonly string $pricePerItem
    ) {
    }
}
