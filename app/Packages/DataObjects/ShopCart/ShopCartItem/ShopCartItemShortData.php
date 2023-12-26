<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\ShopCart\ShopCartItem;

use App\Modules\ShopCart\Models\ShopCartItem;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'shop_cart_item_short_data', type: 'object')]
class ShopCartItemShortData extends Data
{
    public function __construct(
        #[Property(
            property: 'product_offer_id',
            title: 'Идентификатор торгового предложения',
            type: 'integer'
        )]
        public readonly int $product_offer_id,
        #[Property(property: 'count', title: 'Количество', type: 'integer')]
        public readonly int $count,
        #[Property(property: 'selected', title: 'Выбран', type: 'boolean')]
        public readonly bool $selected
    ) {
    }

    public static function fromModel(ShopCartItem $shopCartItem): self
    {
        return new self(
            $shopCartItem->productOffer->id,
            $shopCartItem->count,
            $shopCartItem->selected
        );
    }
}
