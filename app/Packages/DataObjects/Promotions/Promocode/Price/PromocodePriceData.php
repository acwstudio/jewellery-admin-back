<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promocode\Price;

use App\Modules\Promotions\Modules\Promocodes\Models\PromocodePrice;
use App\Packages\DataTransformers\MoneyDecimalTransformer;
use Money\Money;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'promotions_promocode_price_data',
    description: 'Цена промокода',
    type: 'object'
)]
class PromocodePriceData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'product_offer_id', type: 'integer')]
        public readonly int $product_offer_id,
        #[Property(property: 'shop_cart_token', type: 'string')]
        public readonly string $shop_cart_token,
        #[Property(property: 'price', type: 'integer')]
        #[WithTransformer(MoneyDecimalTransformer::class)]
        public readonly Money $price,
    ) {
    }

    public static function fromModel(PromocodePrice $model): self
    {
        return new self(
            $model->id,
            $model->product_offer_id,
            $model->shop_cart_token,
            $model->price
        );
    }
}
