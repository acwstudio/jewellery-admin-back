<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Catalog;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductVideoUrl;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVideoUrlFactory extends Factory
{
    protected $model = ProductVideoUrl::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'path' => 'video/' . $this->faker->numberBetween(1000, 5000) . '.mp4',
        ];
    }
}
