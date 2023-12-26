<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = Product::factory(20)->create(['setFull' => true]);
        $categories = Category::factory(5)->create();
        $features = Feature::factory(3)->create();

        /** @var Product $product */
        foreach ($products as $product) {
            $product->categories()->attach($categories->random());
            ProductFeature::factory()->create([
                'product_id' => $product,
                'feature_id' => $features->random()
            ]);
        }
    }
}
