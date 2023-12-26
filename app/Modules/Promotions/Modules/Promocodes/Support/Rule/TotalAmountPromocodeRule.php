<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Rule;

use App\Modules\Promotions\Enums\PromotionConditionTypeEnum;
use App\Modules\Promotions\Models\PromotionConditionRule;
use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartItemData;
use App\Packages\Exceptions\Promotions\ApplyPromocodeException;
use App\Packages\Facades\ProductPrice;
use Illuminate\Support\Collection;
use Money\Money;

class TotalAmountPromocodeRule implements PromocodeRuleInterface
{
    /**
     * @throws ApplyPromocodeException
     */
    public function canApply(PromotionConditionRule $rule): bool
    {
        if (PromotionConditionTypeEnum::ONE_TIME_SALES === $rule->type) {
            $this->validate($rule);
            return true;
        }

        return false;
    }

    public function apply(ShopCartData $shopCart, PromotionConditionRule $rule): bool
    {
        /** @var Collection $items */
        $items = $shopCart->items->toCollection();

        /** @var Money $total */
        $total = $items->reduce(function (Money $result, ShopCartItemData $shopCartItem) {
            /** @var Collection $prices */
            $prices = $shopCartItem->prices->toCollection();
            return $result->add(ProductPrice::getPrice($prices)->multiply($shopCartItem->count));
        }, Money::RUB(0));

        return $total->greaterThanOrEqual($rule->total_amount);
    }

    /**
     * @throws ApplyPromocodeException
     */
    private function validate(PromotionConditionRule $rule): void
    {
        if (!$rule->total_amount instanceof Money) {
            $type = get_class($rule->total_amount);
            throw new ApplyPromocodeException("total_amount of type Money\Money expected. Type $type provided");
        }
    }
}
