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
    public function run()
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('products')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

//        $categoryIds = [];
        $catalogIds = DB::table('catalog.categories')->where('parent_id', null)->get();
//        dd($ids);
        $parentIds = DB::table('catalog.categories')->where('parent_id', 1)->pluck('id');
//        dd($parentIds);

        foreach ($catalogIds as $catalogId) {
            $id = DB::table('public.product_categories')->where('name', $catalogId->title)->first();
//            dd($id);
            $ringsIds = DB::table('catalog.product_categories')
                ->select('id')
                ->where('category_id', $catalogId->id)
                ->get();
//            dd($ringsIds);
            $n = 0;
            foreach ($ringsIds as $key => $ringsId) {
//                dump($ringsId->id);
                $product = DB::table('catalog.products')
                    ->find($ringsId->id);

                if ($product) {
                    dump($n++);
                    dump($product->id);
                    dump($id->name);

                    DB::table('products')->insert([
                        'product_category_id' => $id->id,
                        'brand_id' => null,
                        'sku' => $product->sku,
                        'name' => $product->name,
                        'summary' => $product->summary,
                        'description' => $product->description ?? null,
                        'slug' => SlugService::createSlug(Product::class, 'slug', $product->name),
                        'is_active' => true
                    ]);
                }
//            dump($product);
            }
        }
//        $ringsIds = DB::table('catalog.product_categories')
//            ->select('id')
//            ->where('category_id', 6)
//            ->get();
//        dd($productIds);
//        $n = 0;
//        foreach ($ringsIds as $key => $ringsId) {
//            $product = DB::table('catalog.products')
//                ->find($ringsId->id);
//
//            if ($product) {
//                dump($n++);
//                DB::table('products')->insert([
//                'product_category_id' => 3,
//                'brand_id' => null,
//                'sku' => $product->sku,
//                'name' => $product->name,
//                'summary' => $product->summary,
//                'description' => $product->description ?? null,
//                'slug' => SlugService::createSlug(Product::class, 'slug', $product->name),
//                'is_active' => true
//            ]);
//            }
//            dump($product);
//        }
    }
}
