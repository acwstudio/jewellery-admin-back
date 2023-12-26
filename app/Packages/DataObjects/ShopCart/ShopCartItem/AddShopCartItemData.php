<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\ShopCart\ShopCartItem;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'shop_cart_add_item_data',
    required: ['product_offer_id', 'count'],
    type: 'object'
)]
class AddShopCartItemData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(
            property: 'product_id',
            title: 'Идентификатор продукта',
            type: 'integer'
        )]
        #[IntegerType, Min(1), Exists(Product::class, 'id')]
        public readonly int $product_id,
        #[Property(
            property: 'product_offer_id',
            title: 'Идентификатор торгового предложения',
            type: 'integer'
        )]
        #[IntegerType, Min(1), Exists(ProductOffer::class, 'id')]
        public readonly int $product_offer_id,
        #[Property(property: 'count', type: 'integer')]
        #[IntegerType, Min(1)]
        public readonly int $count,
        #[Property(
            property: 'selected',
            title: 'Выбрано для оформления',
            type: 'boolean',
            default: false
        )]
        #[BooleanType]
        public readonly bool $selected = false,
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
