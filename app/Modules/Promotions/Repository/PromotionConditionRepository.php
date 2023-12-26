<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Repository;

use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Packages\DataObjects\Promotions\CreatePromotionCondition;

class PromotionConditionRepository
{
    public function create(Promotion $promotion, CreatePromotionCondition $data): PromotionCondition
    {
        $attributes = $data->except('rules')->toArray();
        /** @var PromotionCondition $promotionCondition */
        $promotionCondition = $promotion->condition()->create($attributes);
        return $promotionCondition;
    }
}
