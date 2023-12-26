<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions\Promocode\Price\Filter;

use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

#[Schema(schema: 'promotions_filter_promocode_price_data', type: 'object')]
class FilterPromocodePriceData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(
            property: 'shop_cart_token',
            description: 'Идентификатор корзины',
            type: 'string',
            nullable: true
        )]
        #[Nullable, StringType]
        public readonly ?string $shop_cart_token = null,
        #[Property(
            property: 'product_offer_id',
            description: 'Идентификатор продуктового предложения',
            type: 'integer',
            nullable: true
        )]
        #[Nullable, IntegerType]
        public readonly ?int $product_offer_id = null,
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
