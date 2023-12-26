<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Repository;

use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Packages\DataObjects\Promotions\CreatePromotionBenefit;

class PromotionBenefitRepository
{
    public function create(Promotion $promotion, CreatePromotionBenefit $data): PromotionBenefit
    {
        $attributes = $data->except('gifts')->toArray();
        /** @var PromotionBenefit $promotionBenefit */
        $promotionBenefit = $promotion->benefits()->create($attributes);
        return $promotionBenefit;
    }
}
