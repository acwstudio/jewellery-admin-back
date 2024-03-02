<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TypePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('type_pages')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        $typePages = [
            'Главный страница','Коллекции','Luxe','Kids',
        ];

        foreach ($typePages as $typePage) {
            DB::table('type_pages')->insert([
                'type' => $typePage,
                'slug' => Str::slug($typePage),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
