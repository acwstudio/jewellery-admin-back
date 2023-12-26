<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Promotions;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Models\PromotionBenefitProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Money;

class PromotionBenefitProductFactory extends Factory
{
    protected $model = PromotionBenefitProduct::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'promotion_benefit_id' => PromotionBenefit::factory(),
            'external_id' => $this->faker->uuid,
            'sku' => (string) $this->faker->randomDigit(),
            'price' => Money::RUB($this->faker->numberBetween(1000, 100000) * 100),
        ];
    }
}
