<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\ShopCart;

use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Packages\DataObjects\Promotions\Promocode\PromocodeData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartItemData;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Illuminate\Support\Facades\App;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[Schema(
    schema: 'shop_cart_data',
    description: 'Корзина пользователя',
    type: 'object'
)]
class ShopCartData extends Data
{
    public function __construct(
        #[Property(property: 'token', type: 'string')]
        public readonly string $token,
        #[Property(
            property: 'items',
            type: 'array',
            items: new Items(ref: '#/components/schemas/shop_cart_item_data')
        )]
        #[DataCollectionOf(ShopCartItemData::class)]
        public readonly DataCollection $items,
        #[Property(property: 'promocode', ref: '#/components/schemas/promotions_promocode_data', type: 'object')]
        public readonly ?PromocodeData $promocode = null
    ) {
    }

    public static function fromModel(ShopCart $shopCart): self
    {
        return new self(
            $shopCart->token,
            self::getShopCartItemDataCollection($shopCart),
            self::getActivePromocodeData($shopCart->token)
        );
    }

    private static function getShopCartItemDataCollection(ShopCart $shopCart): DataCollection
    {
        /** @var \Illuminate\Support\Collection<ShopCartItem> $shopCartItems */
        $shopCartItems = $shopCart->items()->getQuery()->orderBy('created_at', 'desc')->get();

        $shopCartItemDataCollection = $shopCartItems->map(
            fn (ShopCartItem $shopCartItem) => ShopCartItemData::fromModel($shopCartItem)
        );

        return ShopCartItemData::collection($shopCartItemDataCollection);
    }

    private static function getActivePromocodeData(string $shopCartToken): ?PromocodeData
    {
        /** @var PromotionsModuleClientInterface $promotionsModuleClient */
        $promotionsModuleClient = App::make(PromotionsModuleClientInterface::class);
        return $promotionsModuleClient->getActivePromocode($shopCartToken);
    }
}
