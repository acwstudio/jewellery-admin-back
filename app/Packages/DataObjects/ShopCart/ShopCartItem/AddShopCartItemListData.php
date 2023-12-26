<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\ShopCart\ShopCartItem;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'shop_cart_add_item_list_data',
    required: ['items'],
    type: 'object'
)]
class AddShopCartItemListData extends Data
{
    public function __construct(
        #[Property(
            property: 'items',
            type: 'array',
            items: new Items(ref: '#/components/schemas/shop_cart_add_item_data')
        )]
        #[DataCollectionOf(AddShopCartItemData::class)]
        public readonly DataCollection $items
    ) {
    }
}
