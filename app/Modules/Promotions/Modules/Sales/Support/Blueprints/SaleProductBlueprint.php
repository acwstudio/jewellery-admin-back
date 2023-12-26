<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Support\Blueprints;

class SaleProductBlueprint
{
    public function __construct(
        public readonly int $product_id,
    ) {
    }
}
