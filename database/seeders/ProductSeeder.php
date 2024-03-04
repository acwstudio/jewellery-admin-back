<?php

namespace Database\Seeders;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Domain\Catalog\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            ->table('catalog.products')->select(['sku','name','summary','description'])
            ->where('is_active', true)
            ->where('name', 'LIKE', '%цепь%')
            ->get();

        $chains->map(function ($chain) {
            $chain->product_category_id = 19;
            $chain->brand_id = null;
            $chain->slug = Str::slug($chain->name) . '-' . $chain->sku;
            $chain->is_active = true;
            $chain->weight = null;
        });
        $arrChains = $chains->map(fn ($row) => get_object_vars($row))->toArray();

        DB::table('products')->insert($arrChains);

        $bracelets = DB::connection('pgsql_core')
            ->table('catalog.products')->select(['sku','name','summary','description'])
            ->where('is_active', true)
            ->where('name', 'LIKE', '%браслет%')
            ->get();

        $bracelets->map(function ($bracelet) {
            $bracelet->product_category_id = 4;
            $bracelet->brand_id = null;
            $bracelet->slug = Str::slug($bracelet->name) . '-' . $bracelet->sku;
            $bracelet->is_active = true;
            $bracelet->weight = null;
        });

        $arrBracelets = $bracelets->map(fn ($row) => get_object_vars($row))->toArray();

        DB::table('products')->insert($arrBracelets);

        $necklaces = DB::connection('pgsql_core')
            ->table('catalog.products')->select(['sku','name','summary','description'])
            ->where('is_active', true)
            ->where('name', 'LIKE', '%колье%')
            ->get();

        $necklaces->map(function ($necklace) {
            $necklace->product_category_id = 15;
            $necklace->brand_id = null;
            $necklace->slug = Str::slug($necklace->name) . '-' . $necklace->sku;
            $necklace->is_active = true;
            $necklace->weight = null;
        });

        $arrNecklaces = $necklaces->map(fn ($row) => get_object_vars($row))->toArray();

        DB::table('products')->insert($arrNecklaces);

        $query = DB::connection('pgsql_core')
            ->table('catalog.products')->select(['sku','name','summary','description'])
            ->where('is_active', true)
            ->where('name', 'LIKE', '%кольцо%');

        $max = 400;

        $total = $query->count();
        $pages = ceil($total / $max);
        for ($i = 1; $i < ($pages + 1); $i++) {
            $offset = (($i - 1)  * $max);
            $start = ($offset == 0 ? 0 : ($offset + 1));
            $rings = DB::connection('pgsql_core')
                ->table('catalog.products')->select(['sku','name','summary','description'])
                ->where('is_active', true)
                ->where('name', 'LIKE', '%кольцо%')
                ->skip($start)->take($max)->get();

            $rings->map(function ($ring) {
                $ring->product_category_id = 3;
                $ring->brand_id = null;
                $ring->slug = Str::slug($ring->name) . '-' . $ring->sku;
                $ring->is_active = true;
                $ring->weight = null;
            });
            $arrRings = $rings->map(fn ($row) => get_object_vars($row))->toArray();

            DB::table('products')->insert($arrRings);
            dump($max);
        }
    }
}
