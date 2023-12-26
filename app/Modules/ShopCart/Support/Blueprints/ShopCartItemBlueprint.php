<?php

declare(strict_types=1);

namespace App\Modules\ShopCart\Support\Blueprints;

class ShopCartItemBlueprint
{
    public function __construct(
        public readonly int $count,
        public readonly int $product_id,
        public readonly int $product_offer_id,
        public readonly bool $selected,
        public readonly ?string $shop_cart_token = null
    ) {
    }
}
