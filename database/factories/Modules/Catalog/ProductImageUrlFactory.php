<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Catalog;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductImageUrl;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageUrlFactory extends Factory
{
    protected $model = ProductImageUrl::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'path' => 'Production/image.jpg',
            'is_main' => false
        ];
    }
}
