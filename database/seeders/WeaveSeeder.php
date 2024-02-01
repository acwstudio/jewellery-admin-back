<?php

namespace Database\Seeders;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Domain\Catalog\Models\Weave;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        $weaves = DB::table('catalog.features')->where('type', 'weaving')->get();
//        dd($weaves);
        foreach ($weaves as $weave) {
            DB::table('weaves')->insert([
                'name' => $weave->value,
                'slug' => SlugService::createSlug(Weave::class, 'slug', $weave->value),
                'is_active' => true
            ]);
        }
    }
}
