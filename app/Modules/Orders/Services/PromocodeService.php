<?php

declare(strict_types=1);

namespace App\Modules\Orders\Services;

use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;

class PromocodeService
{
    public function __construct(
        private readonly ShopCartModuleClientInterface $shopCartModuleClient,
        private readonly PromotionsModuleClientInterface $promotionsModuleClient
    ) {
    }

    public function getPromotionExternalId(): ?string
    {
        $shopCart = $this->shopCartModuleClient->getShopCart();
        $promocode = $this->promotionsModuleClient->getActivePromocode($shopCart->token);
        return $promocode?->promotionExternalId;
    }
}
