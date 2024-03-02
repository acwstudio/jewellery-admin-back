<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TypePageSeeder::class);
        $this->call(TypeBannerSeeder::class);
        $this->call(TypeDeviceSeeder::class);
        $this->call(ImageBannerSeeder::class);
        $this->call(BannerSeeder::class);
    }
}
