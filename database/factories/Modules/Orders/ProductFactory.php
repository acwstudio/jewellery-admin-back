<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Orders;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Money;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'product_offer_id' => ProductOffer::factory(),
            'guid' => $this->faker->uuid,
            'sku' => $this->faker->numerify,
            'count' => 1,
            'price' => Money::RUB(rand(1000, 10000) * 100),
            'amount' => Money::RUB(rand(1000, 10000) * 100),
            'size' => null
        ];
    }
}
