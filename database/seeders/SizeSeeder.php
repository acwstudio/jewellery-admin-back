<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('sizes')->truncate();
        DB::table('size_categories')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        $sizeCategories = ['размер кольца','размер браслета','без размера'];

        $ringSizes = ['15','15.5','16','16.5','17','17.5','18','18.5','19','19.5','20','20.5','21','21.5','22'];
        $braceletSizes = ['18','19','20','21','22','23'];

        foreach ($sizeCategories as $sizeCategory) {
            DB::table('size_categories')->insert([
                'type' => $sizeCategory,
                'slug' => Str::slug($sizeCategory),
                'is_active' => true
            ]);
        }

        $bracelets = DB::table('products')->where('product_category_id', 4)->get();
        foreach ($bracelets as $bracelet) {
            foreach ($braceletSizes as $braceletSize) {
                DB::table('sizes')->insert([
                    'product_id' => $bracelet->id,
                    'size_category_id' => 2,
                    'value' => $braceletSize,
                    'is_active' => Arr::random([true,false])
                ]);
            }
        }

        $rings = DB::table('products')->where('product_category_id', 3)->get();
        foreach ($rings as $ring) {
            foreach ($ringSizes as $ringSize) {
                DB::table('sizes')->insert([
                    'product_id' => $ring->id,
                    'size_category_id' => 1,
                    'value' => $ringSize,
                    'is_active' => Arr::random([true,false])
                ]);
            }
        }

        $products = DB::table('products')
            ->where('product_category_id', '!=', 3)
            ->where('product_category_id', '!=', 2)
            ->get();
        foreach ($products as $product) {
//            foreach ($ringSizes as $ringSize) {
                DB::table('sizes')->insert([
                    'product_id' => $product->id,
                    'size_category_id' => 3,
                    'value' => 0.0,
                    'is_active' => true
                ]);
//            }
        }
    }
}
