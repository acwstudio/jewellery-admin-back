<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Illuminate\Database\Seeder;

class ProductOfferPriceSeeder extends Seeder
{
    public function run()
    {
        ProductOfferPrice::factory(20)->create([
            'type' => OfferPriceTypeEnum::REGULAR,
            'is_active' => true
        ]);
    }
}
