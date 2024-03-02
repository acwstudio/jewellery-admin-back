<?php

namespace Database\Seeders;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Domain\Catalog\Models\PriceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('prices')->truncate();
        DB::table('price_categories')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        $priceCategories = DB::connection('pgsql_core')->table('catalog.product_offer_prices')->select('type')
            ->distinct()->pluck('type');

        foreach ($priceCategories as $priceCategory) {
            DB::table('price_categories')->insert([
                'name'      => $priceCategory,
                'is_active' => true,
                'slug'      => SlugService::createSlug(PriceCategory::class, 'slug', $priceCategory)
            ]);
        }

        $offers = DB::connection('pgsql_core')->table('catalog.product_offers')->get();

        foreach ($offers as $offer) {
            $coreProduct = DB::connection('pgsql_core')->table('catalog.products')
                ->where('id', $offer->product_id)->first();
            $size       = $offer->size;
            $weight     = $offer->weight;
            $offer_id   = $offer->id;
            $offerPrices = DB::connection('pgsql_core')->table('catalog.product_offer_prices')
                ->where('is_active', true)
                ->where('product_offer_id', $offer_id)
                ->get();
            foreach ($offerPrices as $offerPrice) {
                $id = DB::table('price_categories')->where('name', $offerPrice->type)->first()->id;
                $product = DB::table('products')->where('sku', $coreProduct->sku)->first();

                if ($product) {
                    $product_id = $product->id;
                    DB::table('prices')->insert([
                        'product_id' => $product_id,
                        'price_category_id' => $id,
                        'value' => $offerPrice->price,
                        'is_active' => true
                    ]);
                }
            }

        }
    }
}
