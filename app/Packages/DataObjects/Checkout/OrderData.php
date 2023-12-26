<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Checkout;

use App\Packages\DataObjects\Promotions\Promocode\PromocodeData;
use Illuminate\Support\Collection;
use App\Packages\DataTransformers\MoneyTransformer;
use Money\Money;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(schema: 'checkout_order_data', type: 'object')]
class OrderData extends Data
{
    /**
     * @param Collection<ProductData> $products
     */
    public function __construct(
        #[Property(
            property: 'products',
            type: 'array',
            items: new Items(
                ref: '#/components/schemas/checkout_product_data',
            )
        )]
        public readonly Collection $products,
        #[MapName('products_count')]
        #[Property(property: 'products_count', type: 'integer')]
        public readonly int $productsCount,
        #[WithTransformer(MoneyTransformer::class)]
        #[MapName('products_total')]
        #[Property(property: 'products_total', type: 'string')]
        public readonly Money $productsTotal,
        #[WithTransformer(MoneyTransformer::class)]
        #[MapName('products_final_price')]
        #[Property(property: 'final_price', type: 'string')]
        public readonly Money $finalPrice,
        #[WithTransformer(MoneyTransformer::class)]
        #[MapName('discount')]
        #[Property(property: 'discount', type: 'string')]
        public readonly Money $discount,
        #[Property(
            property: 'promocode',
            ref: '#/components/schemas/promotions_promocode_data',
            type: 'object',
            nullable: true
        )]
        public readonly ?PromocodeData $promocode = null
    ) {
    }
}
