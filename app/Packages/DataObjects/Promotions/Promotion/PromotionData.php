<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promotion;

use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Packages\DataObjects\Promotions\Promotion\Benefit\PromotionBenefitData;
use Carbon\Carbon;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'promotions_promotion_data',
    description: 'Продвижение',
    type: 'object'
)]
class PromotionData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'description', type: 'string', nullable: true)]
        public readonly ?string $title,
        #[Property(property: 'is_active', type: 'boolean')]
        public readonly bool $is_active,
        #[Property(property: 'started_at', type: 'string', format: 'date-time', example: '2023-03-09T10:56:00+00:00')]
        public readonly Carbon $started_at,
        #[Property(property: 'expired_at', type: 'string', format: 'date-time', example: '2023-03-09T10:56:00+00:00')]
        public readonly Carbon $expired_at,
        #[Property(
            property: 'benefits',
            type: 'array',
            items: new Items(ref: '#/components/schemas/promotions_promotion_benefit_data')
        )]
        #[DataCollectionOf(PromotionBenefitData::class)]
        public readonly DataCollection $benefits
    ) {
    }

    public static function fromModel(Promotion $model): self
    {
        return new self(
            $model->id,
            $model->description,
            $model->is_active,
            $model->condition->start_at,
            $model->condition->finish_at,
            self::getPromotionBenefitGiftDataCollection($model)
        );
    }

    private static function getPromotionBenefitGiftDataCollection(Promotion $model): DataCollection
    {
        $items = $model->benefits->map(
            fn (PromotionBenefit $item) => PromotionBenefitData::fromModel($item)
        );

        return PromotionBenefitData::collection($items);
    }
}
