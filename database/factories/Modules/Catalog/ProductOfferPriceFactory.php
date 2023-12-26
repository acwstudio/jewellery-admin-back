<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Catalog;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Money;

class ProductOfferPriceFactory extends Factory
{
    protected $model = ProductOfferPrice::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'product_offer_id' => ProductOffer::factory(),
            'price' => Money::RUB($this->faker->numberBetween(1000, 100000) * 100),
            'type' => fake()->randomElement(OfferPriceTypeEnum::cases()),
            'is_active' => true
        ];
    }
}
