<?php

namespace Database\Seeders;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Domain\Catalog\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('products')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        $chains = DB::connection('pgsql_core')
            ->table('catalog.products')
            ->where('name', 'LIKE', '%цепь%')
            ->get();

        foreach ($chains as $chain) {
            DB::table('products')->insert([
                'product_category_id' => 19,
                'brand_id' => null,
                'sku' => $chain->sku,
                'name' => $chain->name,
                'slug' => SlugService::createSlug(Product::class, 'slug', $chain->name),
                'summary' => $chain->summary,
                'description' => $chain->description,
                'is_active' => true,
                'weight' => null,
            ]);
        }

        $bracelets = DB::connection('pgsql_core')
            ->table('catalog.products')
            ->where('name', 'LIKE', '%браслет%')
            ->get();

        foreach ($bracelets as $bracelet) {
//            dump($bracelet->name);
            DB::table('products')->insert([
                'product_category_id' => 4,
                'brand_id' => null,
                'sku' => $bracelet->sku,
                'name' => $bracelet->name,
                'slug' => SlugService::createSlug(Product::class, 'slug', $bracelet->name),
                'summary' => $bracelet->summary,
                'description' => $bracelet->description,
                'is_active' => true,
                'weight' => null,
            ]);
        }

        $necklaces = DB::connection('pgsql_core')
            ->table('catalog.products')
            ->where('name', 'LIKE', '%колье%')
            ->get();

        foreach ($necklaces as $necklace) {
//            dump($necklace->name);
            DB::table('products')->insert([
                'product_category_id' => 15,
                'brand_id' => null,
                'sku' => $necklace->sku,
                'name' => $necklace->name,
                'slug' => SlugService::createSlug(Product::class, 'slug', $necklace->name),
                'summary' => $necklace->summary,
                'description' => $necklace->description,
                'is_active' => true,
                'weight' => null,
            ]);
        }
    }
}
