<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Support\Rule;

use App\Modules\Promotions\Models\PromotionConditionRule;
use App\Packages\DataObjects\ShopCart\ShopCartData;

interface PromocodeRuleInterface
{
    public function canApply(PromotionConditionRule $rule): bool;
    public function apply(ShopCartData $shopCart, PromotionConditionRule $rule): bool;
}
