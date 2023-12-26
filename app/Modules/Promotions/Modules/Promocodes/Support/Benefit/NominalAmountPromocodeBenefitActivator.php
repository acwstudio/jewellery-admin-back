<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Benefit;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Repository\PromocodePriceRepository;
use App\Packages\DataObjects\Promotions\Promocode\CreatePromocodePrice;
use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartItemData;
use App\Packages\Facades\ProductPrice;
use App\Packages\Facades\ShopCart;
use Illuminate\Support\Collection;
use Money\Money;

class NominalAmountPromocodeBenefitActivator extends AbstractAmountPromocodeBenefitActivator
{
    public function canApply(PromotionBenefit $promotionBenefit): bool
    {
        if (
            null !== $promotionBenefit->nominal_amount
            && (int)$promotionBenefit->nominal_amount->getAmount() > 0
        ) {
            return true;
        }

        return false;
    }

    protected function getDiscount(PromotionBenefit $promotionBenefit, Money $total): Money
    {
        $discount = $promotionBenefit->nominal_amount;

        if ($discount->greaterThan($total)) {
            return $total;
        }

        return $discount;
    }
}
