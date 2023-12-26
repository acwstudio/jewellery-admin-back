<?php

declare(strict_types=1);

namespace App\Modules\ShopCart\Support\Blueprints;

class DeleteShopCartItemBlueprint
{
    public function __construct(
        public readonly int $product_offer_id,
        public readonly ?string $shop_cart_token = null,
    ) {
    }
}
