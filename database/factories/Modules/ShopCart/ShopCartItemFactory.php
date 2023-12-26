<?php

declare(strict_types=1);

namespace Database\Factories\Modules\ShopCart;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopCartItemFactory extends Factory
{
    protected $model = ShopCartItem::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'count' => fake()->randomDigit(),
            'shop_cart_id' => ShopCart::factory(),
            'product_offer_id' => ProductOffer::factory(),
            'product_id' => Product::factory()
        ];
    }
}
