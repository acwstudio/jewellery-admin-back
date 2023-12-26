<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Catalog;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductOfferFactory extends Factory
{
    protected $model = ProductOffer::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'size' => $this->faker->unique()->randomFloat(null, 1, 20),
            'weight' => $this->faker->unique()->randomFloat(null, 1, 100)
        ];
    }
}
