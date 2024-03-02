<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TypeBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('type_banners')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        $typeBanners = [
            'Главный слайдер','Второй слайдер','Квадро баннер','Избранные коллекции','Магазины',
        ];

        foreach ($typeBanners as $typeBanner) {
            DB::table('type_banners')->insert([
                'type' => $typeBanner,
                'slug' => Str::slug($typeBanner),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
