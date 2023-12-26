<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Catalog;

use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFeatureFactory extends Factory
{
    protected $model = ProductFeature::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'feature_id' => Feature::factory()
        ];
    }
}
