<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Catalog\Models\Product;
use App\Modules\Collections\Models\Collection;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    public function run()
    {
        $products = Product::factory(100)->create(['setFull' => true]);
        $collections = Collection::factory(20)->create();
        /** @var Collection $collection */
        foreach ($collections as $collection) {
            $collection->products()->sync($products);
        }
    }
}
