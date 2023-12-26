<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promocode;

use App\Modules\Promotions\Modules\Promocodes\Models\PromocodeUsage;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'promotions_promocode_usage_data', type: 'object')]
class PromocodeUsageData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'int')]
        public readonly int $id,
        #[Property(property: 'shop_cart_token', type: 'string')]
        public readonly string $shop_cart_token,
        #[Property(property: 'user_id', type: 'string')]
        public readonly string $user_id,
        #[Property(property: 'is_active', type: 'boolean')]
        public readonly bool $is_active,
        #[Property(property: 'promocode', type: 'string')]
        public readonly string $promocode,
        #[Property(property: 'promotion_external_id', type: 'string')]
        public readonly string $promotion_external_id,
        #[Property(property: 'order_id', type: 'integer', nullable: true)]
        public readonly ?int $order_id = null,
    ) {
    }

    public static function fromModel(PromocodeUsage $model): self
    {
        return new self(
            $model->id,
            $model->shop_cart_token,
            $model->user_id,
            $model->is_active,
            $model->promotionBenefit->promocode,
            $model->promotionBenefit->promotion->external_id,
            $model->order_id
        );
    }
}
