<?php

declare(strict_types=1);

namespace App\Packages\Services;

use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartItemData;
use App\Packages\Facades\ProductPrice;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;
use Illuminate\Support\Collection;
use Money\Money;

class ShopCartService
{
    public function __construct(
        private readonly ShopCartModuleClientInterface $shopCartModuleClient
    ) {
    }

    public function getShopCart(): ShopCartData
    {
        return $this->shopCartModuleClient->getShopCart();
    }

    public function getTotal(): Money
    {
        $shopCart = $this->shopCartModuleClient->getShopCart();
        /** @var Collection $items */
        $items = $shopCart->items->toCollection();

        /** @var Money $total */
        $total = $items->reduce(function (Money $result, ShopCartItemData $shopCartItem) {
            /** @var Collection $prices */
            $prices = $shopCartItem->prices->toCollection();
            return $result->add(ProductPrice::getPrice($prices)->multiply($shopCartItem->count));
        }, Money::RUB(0));

        return $total;
    }
}
