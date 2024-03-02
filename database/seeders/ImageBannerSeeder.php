<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Performance\Models\ImageBanner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImageBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('image_banners')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        $images = [
            '1' => [
                [
                    'love sail','сила любви','конфетти','сальдо','poison','цепи в золоте и серебре','выбери магазин',
                ],
                [
                    'прямой эфир','подвески с символами','изумруды','православные украшения','цепи',
                    'натуральные самоцветы','стильное серебро'
                ],
                [
                    'обручальные кольца','новинки','клевер'
                ],
                ['танзаниты','драгоценные камни','Люкс','Золотые слитки'],
                ['брендовый магазин','брендовый аутлет'],
            ],
            '2' => [
                ['зима в сердце'],
                ['танзаниты','драгоценные камни','Люкс','Золотые слитки']
            ],
            '3' => [
                ['Luxe']
            ]
        ];

        foreach ($images as $key_1 => $image) {
            foreach ($image as $key_2 => $item) {
                foreach ($item as $key_3 => $value) {
                    DB::table('image_banners')->insert([
                        'type_device_id' => 1,
                        'name'           => 'no image',
                        'slug'           => Str::slug('no image'),
                        'model_type'     => ImageBanner::class,
                        'content_link'   => 'https://uvi.ru/catalog/sale/akciia-skidki-do-80?from_main_slider=lovesale',
                        'is_active'      => true,
                        'sequence'       => $key_3 + 1
                    ]);
                }
            }
        }

        foreach ($images as $key_1 => $image) {
            foreach ($image as $key_2 => $item) {
                foreach ($item as $key_3 => $value) {
                    DB::table('image_banners')->insert([
                        'type_device_id' => 2,
                        'name'           => 'no image',
                        'slug'           => Str::slug('no image'),
                        'model_type'     => ImageBanner::class,
                        'content_link'   => 'https://uvi.ru/catalog/sale/akciia-skidki-do-80?from_main_slider=lovesale',
                        'is_active'      => true,
                        'sequence'       => $key_3 + 1
                    ]);
                }
            }
        }
    }
}
