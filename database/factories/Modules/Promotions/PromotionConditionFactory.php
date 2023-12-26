<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Promotions;

use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionCondition;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionConditionFactory extends Factory
{
    protected $model = PromotionCondition::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'promotion_id' => Promotion::factory(),
            'start_at' => Carbon::now(),
            'finish_at' => Carbon::now()->addDays(3)
        ];
    }
}
