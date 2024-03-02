<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Performance\Models\TypeBanner;
use Domain\Performance\Models\TypeDevice;
use Domain\Performance\Models\TypePage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET SESSION_REPLICATION_ROLE="replica";');
        DB::table('banners')->truncate();
        DB::statement('SET SESSION_REPLICATION_ROLE="origin";');

        $imageMainBannersMobile = DB::table('image_banners')->whereBetween('id', [29, 35])->pluck('id');
        $imageMainBannersDesktop = DB::table('image_banners')->whereBetween('id', [1, 7])->pluck('id');
//        dd($imageMainBannersDesktop);
//        $imageBanners = [
//            '1','2','3'
//        ];
        $banners = [
            '1' => ['1', '2', '3', '4', '5',],
            '2' => ['1', '3',],
            '3' => ['1']
        ];

        foreach ($banners as $key => $typePageId) {
            foreach ($typePageId as $key_2 => $typeBannerId) {
                $name = TypePage::find($key)->type . '-' . TypeBanner::find($typeBannerId)->type;
                $bannerId = DB::table('banners')->insertGetId([
                    'type_page_id' => $key,
                    'type_banner_id' => $typeBannerId,
                    'name' => $name,
                    'slug' => \Str::slug($name),
                    'is_active' => true,
                ]);
            }
        }

        $bannersToImageBanners = [
            '1' => [
                '1' => [
                    '1', '2', '3', '4', '5', '6', '7', '30', '31', '32', '33', '34', '35', '36'
                ],
                '2' => [
                    '8', '9', '10', '11', '12', '13', '14', '37', '38', '39', '40', '41', '42', '43'
                ],
                '3' => [
                    '15', '16', '17', '44', '45', '46'
                ],
                '4' => [
                    '18', '19', '20', '21', '47', '48', '49', '50'
                ],
                '5' => [
                    '22', '23', '51', '52'
                ],
            ],
            '2' => [
                '6' => [
                    '24','53'
                ],
                '7' => [
                    '25','26','27','28','54','55','56','57'
                ]
            ],
            '3' => [
                '8' => [
                    '29','58'
                ]
            ]
        ];

        foreach ($bannersToImageBanners as $key_page => $bannersToImageBanner) {
            foreach ($bannersToImageBanner as $key_banner => $value) {
                foreach ($value as $item) {
                    dump($item);
                    DB::table('banner_image_banner')->insert([
                        'banner_id' => $key_banner,
                        'image_banner_id' => $item
                    ]);
                }
            }
        }
    }
}
