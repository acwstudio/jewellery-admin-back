<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Price;

use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Packages\DataCasts\MoneyCast;
use App\Packages\DataTransformers\MoneyDecimalTransformer;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Money\Money;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_product_offer_price_data',
    description: 'Цена торгового предложения продукта',
    required: ['id', 'price', 'type', 'is_active'],
    type: 'object'
)]
class ProductOfferPriceData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'price', type: 'integer')]
        #[WithTransformer(MoneyDecimalTransformer::class)]
        #[WithCast(MoneyCast::class, isDecimal: true)]
        public readonly Money $price,
        #[Property(property: 'type')]
        public readonly OfferPriceTypeEnum $type
    ) {
    }

    public static function fromModel(ProductOfferPrice $productOfferPrice): self
    {
        return new self(
            $productOfferPrice->id,
            $productOfferPrice->price,
            $productOfferPrice->type
        );
    }

    public static function customFromArray(array $productOfferPrice): self
    {
        return new self(
            $productOfferPrice['id'],
            self::getMoney($productOfferPrice['price']),
            OfferPriceTypeEnum::from($productOfferPrice['type'])
        );
    }

    private static function getMoney(array $price): Money
    {
        return Money::RUB($price['amount'] ?? 0);
    }
}
