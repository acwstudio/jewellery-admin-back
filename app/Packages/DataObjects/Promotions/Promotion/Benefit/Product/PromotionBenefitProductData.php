<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promotion\Benefit\Product;

use App\Modules\Promotions\Models\PromotionBenefitProduct;
use App\Packages\DataTransformers\MoneyDecimalTransformer;
use Money\Money;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'promotions_promotion_benefit_product_data',
    description: 'Продукты преимущества',
    type: 'object'
)]
class PromotionBenefitProductData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'external_id', type: 'string')]
        public readonly string $external_id,
        #[Property(property: 'sku', type: 'string')]
        public readonly string $sku,
        #[Property(property: 'size', type: 'string', nullable: true)]
        public readonly ?string $size,
        #[Property(property: 'price', type: 'integer')]
        #[WithTransformer(MoneyDecimalTransformer::class)]
        public readonly Money $price,
    ) {
    }

    public static function fromModel(PromotionBenefitProduct $model): self
    {
        return new self(
            $model->id,
            $model->external_id,
            $model->sku,
            $model->size,
            $model->price
        );
    }
}
