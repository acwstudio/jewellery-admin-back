<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Services;

use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Models\PromotionConditionRule;
use App\Modules\Promotions\Repository\PromotionConditionRulePhoneRepository;
use App\Modules\Promotions\Repository\PromotionConditionRuleRepository;
use App\Packages\DataObjects\Promotions\CreatePromotionConditionRule;
use Illuminate\Support\Collection;

class PromotionConditionRuleService
{
    public function __construct(
        private readonly PromotionConditionRuleRepository $promotionConditionRuleRepository,
        private readonly PromotionConditionRulePhoneRepository $promotionConditionRulePhoneRepository
    ) {
    }

    public function create(
        PromotionCondition $promotionCondition,
        CreatePromotionConditionRule $data
    ): PromotionConditionRule {
        $promotionConditionRule = $this->promotionConditionRuleRepository->create($promotionCondition, $data);

        if ($data->phones !== null) {
            /** @var Collection<CreatePromotionConditionRulePhone> $phones */
            $phones = $data->phones->toCollection();

            foreach ($phones as $phone) {
                $this->promotionConditionRulePhoneRepository->create($promotionConditionRule, $phone->phone);
            }
        }

        return $promotionConditionRule->refresh();
    }
}
