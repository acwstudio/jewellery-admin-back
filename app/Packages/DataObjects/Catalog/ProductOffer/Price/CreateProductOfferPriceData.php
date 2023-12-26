<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductOffer\Price;

use App\Modules\Catalog\Models\ProductOffer;
use App\Packages\DataCasts\MoneyCast;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Money\Money;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'catalog_create_product_offer_price_data',
    description: 'Создание цены торгового предложения продукта',
    required: ['price', 'type'],
    type: 'object'
)]
class CreateProductOfferPriceData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[MapInputName('id'), IntegerType, Min(1), Exists(ProductOffer::class, 'id')]
        public readonly int $product_offer_id,
        #[Property(property: 'price', type: 'integer')]
        #[IntegerType, Min(0), WithCast(MoneyCast::class, isDecimal: true)]
        public readonly Money $price,
        #[Property(property: 'type')]
        public readonly OfferPriceTypeEnum $type
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
