<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Services;

use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Repository\PromotionBenefitGiftRepository;
use App\Modules\Promotions\Repository\PromotionBenefitProductRepository;
use App\Modules\Promotions\Repository\PromotionBenefitRepository;
use App\Packages\DataObjects\Promotions\CreatePromotionBenefit;
use Illuminate\Support\Collection;

class PromotionBenefitService
{
    public function __construct(
        private readonly PromotionBenefitRepository $promotionBenefitRepository,
        private readonly PromotionBenefitGiftRepository $promotionBenefitGiftRepository,
        private readonly PromotionBenefitProductRepository $promotionBenefitProductRepository
    ) {
    }

    public function create(Promotion $promotion, CreatePromotionBenefit $data): PromotionBenefit
    {
        $promotionBenefit = $this->promotionBenefitRepository->create($promotion, $data);

        /** @var Collection $gifts */
        $gifts = $data->gifts?->toCollection() ?? new Collection();
        foreach ($gifts as $gift) {
            $this->promotionBenefitGiftRepository->create($promotionBenefit, $gift);
        }

        /** @var Collection $products */
        $products = $data->products?->toCollection() ?? new Collection();
        foreach ($products as $product) {
            $this->promotionBenefitProductRepository->create($promotionBenefit, $product);
        }

        return $promotionBenefit->refresh();
    }
}
