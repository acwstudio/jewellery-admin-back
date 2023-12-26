<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Benefit;

use App\Modules\Promotions\Models\PromotionBenefit;

interface PromocodeBenefitActivatorInterface
{
    public function canApply(PromotionBenefit $promotionBenefit): bool;
    public function apply(PromotionBenefit $promotionBenefit): void;
    public function cancel(PromotionBenefit $promotionBenefit): void;
}
