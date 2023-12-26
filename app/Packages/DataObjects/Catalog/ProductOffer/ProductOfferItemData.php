<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer;

use App\Modules\Catalog\Models\ProductOffer;
use App\Packages\DataCasts\MoneyCast;
use App\Packages\DataTransformers\MoneyDecimalTransformer;
use Money\Money;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_product_offer_item_data',
    description: 'Торговое предложение продукта',
    required: ['id', 'size', 'count', 'price', 'price_old'],
    type: 'object'
)]
class ProductOfferItemData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'size', description: 'Размер', type: 'string', nullable: true)]
        public readonly ?string $size,
        #[Property(property: 'count', description: 'Количество', type: 'integer')]
        public readonly int $count,
        #[Property(property: 'price', type: 'integer')]
        #[WithTransformer(MoneyDecimalTransformer::class)]
        #[WithCast(MoneyCast::class, isDecimal: true)]
        public readonly Money $price,
        #[Property(property: 'price_old', type: 'integer', nullable: true)]
        #[WithTransformer(MoneyDecimalTransformer::class)]
        #[WithCast(MoneyCast::class, isDecimal: true)]
        public readonly ?Money $price_old = null,
    ) {
    }

    public static function fromModel(ProductOffer $productOffer): self
    {
        $productOffer = $productOffer->toSearchableArray();
        return self::customFromArray($productOffer);
    }

    public static function customFromArray(array $productOffer): self
    {
        $prices = self::getPrices($productOffer);

        return new self(
            id: $productOffer['id'],
            size: $productOffer['size'] ?? null,
            count: $productOffer['stock'] ?? 0,
            price: $prices['price'],
            price_old: $prices['price_old']
        );
    }

    private static function getPrices(array $productOffer): array
    {
        $regularPrice = $productOffer['regular_price'] ?? 0;
        $prices = [
            'price' => self::getMoney($regularPrice),
            'price_old' => null,
        ];
        $discount = $productOffer['discount'] ?? 0;
        $promoPrice = $productOffer['promo_price'] ?? 0;
        if ($discount > 0 && $promoPrice > 0) {
            $oldPrice = $prices['price'];
            $prices = [
                'price' => self::getMoney($promoPrice),
                'price_old' => $oldPrice
            ];
        }

        return $prices;
    }

    private static function getMoney(int|string $amount): Money
    {
        return Money::RUB($amount);
    }
}
