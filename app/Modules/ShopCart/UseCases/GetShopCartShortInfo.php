<?php

declare(strict_types=1);

namespace App\Modules\ShopCart\UseCases;

use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Modules\ShopCart\Services\ShopCartService;
use App\Packages\DataObjects\ShopCart\ShopCartShortData;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;

class GetShopCartShortInfo
{
    public function __construct(
        private readonly ShopCartService $shopCartService,
        private readonly PromotionsModuleClientInterface $promotionsModuleClient,
    ) {
    }

    public function __invoke(?string $token): ShopCartShortData
    {
        $shopCart = $this->shopCartService->getOrCreateShopCart($token);
        return $this->createShopCartData($shopCart);
    }

    private function createShopCartData(ShopCart $shopCart): ShopCartShortData
    {
        return new ShopCartShortData(
            token: $shopCart->token,
            count: $this->getShopCartCount($shopCart),
            promocode: $this->promotionsModuleClient->getActivePromocode($shopCart->token)
        );
    }

    private function getShopCartCount(ShopCart $shopCart): int
    {
        return $shopCart->items->sum(function (ShopCartItem $item) {
            return $item->count;
        });
    }
}
