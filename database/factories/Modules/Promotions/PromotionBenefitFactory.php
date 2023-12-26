<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Promotions;

use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionBenefitFactory extends Factory
{
    protected $model = PromotionBenefit::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'promotion_id' => Promotion::factory(),
            'type' => fake()->randomElement(PromotionBenefitTypeEnum::cases()),
            'promocode' => ''
        ];
    }
}
