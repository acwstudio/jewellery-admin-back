<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Promotions;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodePrice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Money;

class PromocodePriceFactory extends Factory
{
    protected $model = PromocodePrice::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'product_offer_id' => $this->faker->randomDigit(),
            'shop_cart_token' => $this->faker->uuid,
            'price' => Money::RUB($this->faker->numberBetween(1000, 100000) * 100),
            'promotion_benefit_id' => PromotionBenefit::factory(),
        ];
    }
}
