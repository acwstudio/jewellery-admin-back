<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promotion\Benefit;

use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Enums\PromotionBenefitTypeFormEnum;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Models\PromotionBenefitGift;
use App\Modules\Promotions\Models\PromotionBenefitProduct;
use App\Packages\DataObjects\Promotions\Promotion\Benefit\Gift\PromotionBenefitGiftData;
use App\Packages\DataObjects\Promotions\Promotion\Benefit\Product\PromotionBenefitProductData;
use App\Packages\DataTransformers\MoneyDecimalTransformer;
use Money\Money;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'promotions_promotion_benefit_data',
    description: 'Промо преимущества',
    type: 'object'
)]
class PromotionBenefitData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'type')]
        public readonly PromotionBenefitTypeEnum $type,
        #[Property(property: 'type_form', nullable: true)]
        public readonly ?PromotionBenefitTypeFormEnum $type_form,
        #[Property(property: 'promocode', type: 'string')]
        public readonly string $promocode,
        #[Property(property: 'nominal_amount', type: 'integer', nullable: true)]
        #[WithTransformer(MoneyDecimalTransformer::class)]
        public readonly ?Money $nominal_amount,
        #[Property(property: 'percent_amount', type: 'integer', nullable: true)]
        public readonly ?int $percent_amount,
        #[Property(property: 'max_nominal_amount', type: 'integer', nullable: true)]
        #[WithTransformer(MoneyDecimalTransformer::class)]
        public readonly ?Money $max_nominal_amount,
        #[Property(property: 'use_count', type: 'integer', nullable: true)]
        public readonly ?int $use_count,
        #[Property(property: 'is_free_delivery', type: 'boolean', nullable: true)]
        public readonly ?bool $is_free_delivery,
        #[Property(property: 'is_gift', type: 'boolean', nullable: true)]
        public readonly ?bool $is_gift,
        #[Property(property: 'is_gift_from_shop_cart', type: 'boolean', nullable: true)]
        public readonly ?bool $is_gift_from_shop_cart,
        #[Property(property: 'gift_from_shop_cart_count', type: 'integer', nullable: true)]
        public readonly ?int $gift_from_shop_cart_count,
        #[Property(
            property: 'gifts',
            type: 'array',
            items: new Items(ref: '#/components/schemas/promotions_promotion_benefit_gift_data')
        )]
        #[DataCollectionOf(PromotionBenefitGiftData::class)]
        public readonly DataCollection $gifts,
        #[Property(
            property: 'products',
            type: 'array',
            items: new Items(ref: '#/components/schemas/promotions_promotion_benefit_gift_data')
        )]
        #[DataCollectionOf(PromotionBenefitProductData::class)]
        public readonly DataCollection $products
    ) {
    }

    public static function fromModel(PromotionBenefit $model): self
    {
        return new self(
            $model->id,
            $model->type,
            $model->type_form,
            $model->promocode,
            $model->nominal_amount,
            $model->percent_amount,
            $model->max_nominal_amount,
            $model->use_count,
            $model->is_free_delivery,
            $model->is_gift,
            $model->is_gift_from_shop_cart,
            $model->gift_from_shop_cart_count,
            self::getPromotionBenefitGiftDataCollection($model),
            self::getPromotionBenefitProductDataCollection($model)
        );
    }

    private static function getPromotionBenefitGiftDataCollection(PromotionBenefit $model): DataCollection
    {
        $items = $model->gifts->map(
            fn (PromotionBenefitGift $item) => PromotionBenefitGiftData::fromModel($item)
        );

        return PromotionBenefitGiftData::collection($items);
    }

    private static function getPromotionBenefitProductDataCollection(PromotionBenefit $model): DataCollection
    {
        $items = $model->products->map(
            fn (PromotionBenefitProduct $item) => PromotionBenefitProductData::fromModel($item)
        );

        return PromotionBenefitProductData::collection($items);
    }
}
