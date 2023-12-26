<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Promocodes\Repository;

use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Models\PromotionBenefit;
use Illuminate\Database\Eloquent\Builder;

class PromocodeRepository
{
    public function getByPromotionExternalId(string $promotionExternalId): ?PromotionBenefit
    {
        /** @var PromotionBenefit|null $promotionBenefit */
        $promotionBenefit = PromotionBenefit::query()
            ->whereHas('promotion', function (Builder $query) use ($promotionExternalId) {
                $query->where('external_id', $promotionExternalId);
            })
            ->first();

        return $promotionBenefit;
    }

    public function getByPromocode(string $promocode): ?PromotionBenefit
    {
        /** @var PromotionBenefit|null $promotionBenefit */
        $promotionBenefit = PromotionBenefit::query()
            ->where([
                'type' => PromotionBenefitTypeEnum::PROMOCODE->value,
                'promocode' => $promocode,
            ])
            ->first();

        return $promotionBenefit;
    }
}
