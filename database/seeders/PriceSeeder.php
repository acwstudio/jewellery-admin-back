<?php

namespace Database\Seeders;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Domain\Catalog\Models\PriceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

        $price_categories = ['regular','live','promo'];

        foreach ($price_categories as $price_category) {
//            dd($price_category);
            DB::table('price_categories')->insert([
                'name' => $price_category,
                'slug' => Str::slug($price_category),
                'is_active' => true
            ]);
        }

        DB::unprepared(file_get_contents(base_path() . '/database/seeders/sql/core_prices_create.sql'));
        DB::unprepared(file_get_contents(base_path() . '/database/seeders/sql/core_prices_insert.sql'));
        DB::unprepared(file_get_contents(base_path() . '/database/seeders/sql/prices_insert.sql'));
    }
}
