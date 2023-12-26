<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Promotions\Models;

use App\Modules\Promotions\Models\PromotionConditionRule;
use App\Modules\Promotions\Models\PromotionConditionRulePhone;
use App\Packages\Support\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class PromotionConditionRulePhoneFactory extends Factory
{
    protected $model = PromotionConditionRulePhone::class;

    /**
     * @inheritDoc
     * @throws NumberParseException
     */
    public function definition(): array
    {
        $promotionConditionRuleId = PromotionConditionRule::factory();
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        $phoneNumber = $phoneNumberUtil->parse(
            '+' . $this->faker->phoneNumber(),
            'RU',
            new PhoneNumber()
        );

        return [
            'promotion_condition_rule_id' => $promotionConditionRuleId,
            'phone' => $phoneNumber
        ];
    }
}
