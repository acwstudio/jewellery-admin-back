<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promotion\Benefit\Gift;

use App\Modules\Promotions\Models\PromotionBenefitGift;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'promotions_promotion_benefit_gift_data',
    description: 'Подарки преимущества',
    type: 'object'
)]
class PromotionBenefitGiftData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'external_id', type: 'string')]
        public readonly string $external_id,
        #[Property(property: 'size', type: 'string')]
        public readonly string $size,
        #[Property(property: 'count', type: 'integer')]
        public readonly int $count
    ) {
    }

    public static function fromModel(PromotionBenefitGift $model): self
    {
        return new self(
            $model->id,
            $model->external_id,
            $model->size,
            $model->count
        );
    }
}
