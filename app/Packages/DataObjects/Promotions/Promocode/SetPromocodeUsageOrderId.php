<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promocode;

use Spatie\LaravelData\Data;

class SetPromocodeUsageOrderId extends Data
{
    public function __construct(
        public readonly int $promocode_usage_id,
        public readonly int $order_id
    ) {
    }
}
