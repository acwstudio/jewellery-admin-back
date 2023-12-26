<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Promotions\Models;

use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Models\PromotionConditionRule;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionConditionRuleFactory extends Factory
{
    protected $model = PromotionConditionRule::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'promotion_condition_id' => PromotionCondition::factory(),
        ];
    }
}
