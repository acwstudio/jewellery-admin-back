<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Repository;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Models\PromotionBenefitGift;
use App\Packages\DataObjects\Promotions\CreatePromotionBenefitGift;

class PromotionBenefitGiftRepository
{
    public function create(PromotionBenefit $promotionBenefit, CreatePromotionBenefitGift $data): PromotionBenefitGift
    {
        /** @var PromotionBenefitGift $promotionBenefitGift */
        $promotionBenefitGift = $promotionBenefit->gifts()->create($data->toArray());
        return $promotionBenefitGift;
    }
}
