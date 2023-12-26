<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Support\DataNormalizer\RabbitMQ;

use App\Modules\Promotions\Support\DataNormalizer\DataNormalizerInterface;
use App\Packages\DataObjects\Promotions\CreatePromotion;
use App\Packages\DataObjects\Promotions\CreatePromotionBenefit;
use App\Packages\DataObjects\Promotions\CreatePromotionCondition;
use App\Packages\DataObjects\Promotions\CreatePromotionPromotion;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class PromotionDataNormalizer implements DataNormalizerInterface
{
    public function normalize($data): Data
    {
        return new CreatePromotion(
            $this->getCreatePromotionPromotionData($data['promotion']),
            $this->getCreatePromotionConditionData($data['conditions']),
            $this->getCreatePromotionBenefitDataCollection($data['sale'])
        );
    }

    private function getCreatePromotionPromotionData(array $promotionData): CreatePromotionPromotion
    {
        return CreatePromotionPromotion::from($promotionData);
    }

    private function getCreatePromotionConditionData(array $conditionData): CreatePromotionCondition
    {
        return CreatePromotionCondition::from($conditionData);
    }

    private function getCreatePromotionBenefitDataCollection(array $saleData): DataCollection
    {
        $items = new Collection();

        foreach ($saleData as $data) {
            if (!empty($data['promokode']) && is_array($data['promokode'])) {
                $this->setCreatePromotionBenefitDataByPromocode($items, $data);
                continue;
            }

            $items->add(CreatePromotionBenefit::from($data));
        }

        return CreatePromotionBenefit::collection($items);
    }

    private function setCreatePromotionBenefitDataByPromocode(Collection $items, array $data): void
    {
        foreach ($data['promokode'] as $promocode) {
            $currentData = $data;
            $currentData['promokode'] = $promocode;
            $items->add(CreatePromotionBenefit::from($currentData));
        }
    }
}
