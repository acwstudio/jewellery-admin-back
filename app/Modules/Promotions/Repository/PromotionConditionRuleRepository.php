<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Repository;

use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Models\PromotionConditionRule;
use App\Packages\DataObjects\Promotions\CreatePromotionConditionRule;

class PromotionConditionRuleRepository
{
    public function create(
        PromotionCondition $condition,
        CreatePromotionConditionRule $data
    ): PromotionConditionRule {
        $attributes = $data->except('phones')->toArray();

        /** @var PromotionConditionRule $promotionConditionRule */
        $promotionConditionRule = $condition->rules()->create($attributes);
        return $promotionConditionRule;
    }
}
