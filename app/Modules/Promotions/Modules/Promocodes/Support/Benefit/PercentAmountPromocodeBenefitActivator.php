<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Benefit;

use App\Modules\Promotions\Models\PromotionBenefit;
use Money\Money;

class PercentAmountPromocodeBenefitActivator extends AbstractAmountPromocodeBenefitActivator
{
    public function canApply(PromotionBenefit $promotionBenefit): bool
    {
        return null !== $promotionBenefit->percent_amount
            && $promotionBenefit->percent_amount > 0;
    }

    protected function getDiscount(PromotionBenefit $promotionBenefit, Money $total): Money
    {
        $discount = $total->multiply(
            $this->getDiscountRatio($promotionBenefit->percent_amount)
        );

        if (
            $promotionBenefit->max_nominal_amount !== null
            && $promotionBenefit->max_nominal_amount->isZero() === false
        ) {
            $discount = $this->adjustMaxNominalAmount($discount, $promotionBenefit->max_nominal_amount);
        }

        return $discount;
    }

    private function getDiscountRatio(int $percent): float
    {
        return $percent / 100;
    }

    private function adjustMaxNominalAmount(Money $discount, Money $maxNominalAmount): Money
    {
        if ($discount->lessThan($maxNominalAmount)) {
            return $discount;
        }

        return $maxNominalAmount;
    }
}
