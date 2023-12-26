<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Repository;

use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Models\PromotionBenefitProduct;
use App\Packages\DataObjects\Promotions\CreatePromotionBenefitProduct;

class PromotionBenefitProductRepository
{
    public function create(
        PromotionBenefit $promotionBenefit,
        CreatePromotionBenefitProduct $data
    ): PromotionBenefitProduct {
        /** @var PromotionBenefitProduct $promotionBenefitProduct */
        $promotionBenefitProduct = $promotionBenefit->products()->create($data->toArray());
        return $promotionBenefitProduct;
    }
}
