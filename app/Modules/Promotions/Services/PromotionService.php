<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Services;

use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Repository\PromotionRepository;
use App\Packages\DataObjects\Promotions\CreatePromotion;
use App\Packages\DataObjects\Promotions\UpdatePromotion;
use App\Packages\Events\PromotionCreated;
use App\Packages\Events\PromotionUpdated;
use App\Packages\Exceptions\Promotions\CreatePromotionException;

class PromotionService
{
    public function __construct(
        private readonly PromotionRepository $promotionRepository,
        private readonly PromotionConditionService $promotionConditionService,
        private readonly PromotionBenefitService $promotionBenefitService
    ) {
    }

    public function getById(int $id): Promotion
    {
        return $this->promotionRepository->getById($id, true);
    }

    public function getByExternalId(string $externalId): ?Promotion
    {
        return $this->promotionRepository->getByExternalId($externalId);
    }

    /**
     * @throws CreatePromotionException
     */
    public function create(CreatePromotion $data): Promotion
    {
        $promotion = $this->promotionRepository->create($data->promotion);

        $this->promotionConditionService->create(
            $promotion,
            $data->condition
        );

        foreach ($data->benefits as $benefit) {
            $this->promotionBenefitService->create($promotion, $benefit);
        }
        $promotion->refresh();

        PromotionCreated::dispatch($promotion->id);

        return $promotion;
    }

    public function update(Promotion $promotion, UpdatePromotion $data): Promotion
    {
        $this->promotionRepository->update($promotion, $data);

        PromotionUpdated::dispatch($promotion->id);

        return $promotion;
    }
}
