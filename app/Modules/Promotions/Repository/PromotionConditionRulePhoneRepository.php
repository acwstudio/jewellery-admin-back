<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Repository;

use App\Modules\Promotions\Models\PromotionConditionRule;
use App\Modules\Promotions\Models\PromotionConditionRulePhone;
use App\Packages\Support\PhoneNumber;

class PromotionConditionRulePhoneRepository
{
    public function create(PromotionConditionRule $rule, PhoneNumber $phone): PromotionConditionRulePhone
    {
        /** @var PromotionConditionRulePhone $promotionConditionRulePhone */
        $promotionConditionRulePhone = $rule->phones()->create([
            'phone' => $phone,
        ]);

        return $promotionConditionRulePhone;
    }

    public function delete(PromotionConditionRulePhone $phone): void
    {
        $phone->delete();
    }
}
