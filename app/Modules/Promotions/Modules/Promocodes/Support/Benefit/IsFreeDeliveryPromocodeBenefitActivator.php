<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Benefit;

use App\Modules\Promotions\Models\PromotionBenefit;

class IsFreeDeliveryPromocodeBenefitActivator implements PromocodeBenefitActivatorInterface
{
    public function canApply(PromotionBenefit $promotionBenefit): bool
    {
        if (null !== $promotionBenefit->is_free_delivery) {
            return true;
        }

        return false;
    }

    public function apply(PromotionBenefit $promotionBenefit): void
    {
    }

    public function cancel(PromotionBenefit $promotionBenefit): void
    {
    }
}
