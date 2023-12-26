<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\ShopCart\ShopCartItem;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Schema(
    schema: 'shop_cart_delete_item_list_data',
    required: ['product_offer_ids'],
    type: 'object'
)]
class DeleteShopCartItemListData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(
            property: 'product_offer_ids',
            description: 'Принимает массив идентификаторов торговых предложений',
            type: 'array',
            items: new Items(type: 'integer')
        )]
        #[ArrayType]
        public readonly array $product_offer_ids = []
        /** @codingStandardsIgnoreEnd */
    ) {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'product_offer_ids.*' => ['integer', 'min:1']
        ];
    }
}
