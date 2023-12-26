<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\ShopCart;

use App\Modules\ShopCart\Models\ShopCart;
use App\Packages\DataObjects\Promotions\Promocode\PromocodeData;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use Illuminate\Support\Facades\App;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'shop_cart_short_data',
    description: 'Краткая информация по корзине пользователя',
    type: 'object'
)]
class ShopCartShortData extends Data
{
    public function __construct(
        #[Property(property: 'token', type: 'string')]
        public readonly string $token,
        #[Property(property: 'count', type: 'integer')]
        public readonly int $count,
        #[Property(property: 'promocode', ref: '#/components/schemas/promotions_promocode_data', type: 'object')]
        public readonly ?PromocodeData $promocode = null
    ) {
    }

    public static function fromModel(ShopCart $shopCart): self
    {
        return new self(
            $shopCart->token,
            self::getShopCartCount($shopCart),
            self::getActivePromocodeData($shopCart->token)
        );
    }

    private static function getShopCartCount(ShopCart $shopCart): int
    {
        return $shopCart->items()->getQuery()->count();
    }

    private static function getActivePromocodeData(string $shopCartToken): ?PromocodeData
    {
        /** @var PromotionsModuleClientInterface $promotionsModuleClient */
        $promotionsModuleClient = App::make(PromotionsModuleClientInterface::class);
        return $promotionsModuleClient->getActivePromocode($shopCartToken);
    }
}
