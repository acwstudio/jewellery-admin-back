<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BlogCategorySeeder::class);
        $this->call(BlogPostSeeder::class);
        $this->call(TypePageSeeder::class);
        $this->call(TypeBannerSeeder::class);
        $this->call(TypeDeviceSeeder::class);
        $this->call(ImageBannerSeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(WeaveSeeder::class);
        $this->call(SizeSeeder::class);
        $this->call(PriceSeeder::class);
    }
}
