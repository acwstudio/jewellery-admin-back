<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Filters;

class PromocodePriceFilter
{
    public function __construct(
        public readonly ?string $shop_cart_token = null,
        public readonly ?int $product_offer_id = null,
    ) {
    }
}
