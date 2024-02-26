<?php

namespace Database\Seeders;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Domain\Catalog\Models\Weave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('weaves')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        $oldWeaves = DB::connection('pgsql_core')->table('catalog.features')->where('type', 'weaving')->get();

        foreach ($oldWeaves as $oldWeave) {
            DB::table('weaves')->insert([
                'name' => $oldWeave->value,
                'slug' => SlugService::createSlug(Weave::class, 'slug', $oldWeave->value),
                'is_active' => true
            ]);
        }

        $weaves = DB::table('weaves')->get();

        $productWeaves = DB::table('products')->where('summary', 'LIKE', '%плетение%')->get();

        foreach ($weaves as $weave) {
            $name = $weave->name;
            $weaveId = $weave->id;
            $items = DB::table('products')->where('summary', 'LIKE', "%{$name}%")->get();
//            dd(DB::table('products')->where('summary', 'LIKE', "%{$name}%")->get());
            foreach ($items as $item) {
                DB::table('product_weave')->insert([
                    'product_id' => $item->id,
                    'weave_id' => $weaveId,
                ]);
            }
        }

//        $weaves = DB::connection('pgsql_core')->table('catalog.features')->where('type', 'weaving')->get();

    }
}
