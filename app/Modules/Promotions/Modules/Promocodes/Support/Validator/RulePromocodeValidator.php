<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Validator;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Models\PromotionConditionRule;
use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;

class RulePromocodeValidator implements PromocodeValidatorInterface
{
    public function __construct(
        private readonly ShopCartModuleClientInterface $shopCartModuleClient,
        private readonly iterable $promocodeRules
    ) {
    }

    public function validate(PromotionBenefit $promocode): bool
    {
        $shopCart = $this->shopCartModuleClient->getShopCart();

        foreach ($promocode->promotion->condition->rules as $rule) {
            if ($this->applyRule($shopCart, $rule) === false) {
                return false;
            }
        }

        return true;
    }

    private function applyRule(ShopCartData $shopCart, PromotionConditionRule $promotionConditionRule): bool
    {
        foreach ($this->promocodeRules as $rule) {
            if (
                $rule->canApply($promotionConditionRule)
                && $rule->apply($shopCart, $promotionConditionRule) === false
            ) {
                return false;
            }
        }

        return true;
    }
}
