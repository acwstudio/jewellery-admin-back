<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Filters;

class PromocodeUsageFilter
{
    public function __construct(
        public readonly ?int $promotion_benefit_id = null,
        public readonly ?string $shop_cart_token = null,
        public readonly ?bool $is_active = null,
        public readonly ?int $order_id = null,
    ) {
    }
}
