<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Services;

use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Repository\PromotionConditionRepository;
use App\Packages\DataObjects\Promotions\CreatePromotionCondition;
use App\Packages\Exceptions\Promotions\CreatePromotionException;
use Illuminate\Support\Collection;

class PromotionConditionService
{
    public function __construct(
        private readonly PromotionConditionRepository $promotionConditionRepository,
        private readonly PromotionConditionRuleService $promotionConditionRuleService
    ) {
    }

    /**
     * @throws CreatePromotionException
     */
    public function create(Promotion $promotion, CreatePromotionCondition $data): PromotionCondition
    {
        $promotionCondition = $this->promotionConditionRepository->create($promotion, $data);

        /** @var Collection $rules */
        $rules = $data->rules->toCollection();

        foreach ($rules as $rule) {
            $this->promotionConditionRuleService->create(
                $promotionCondition,
                $rule
            );
        }

        return $promotionCondition->refresh();
    }
}
