<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\AddShopCartItemListData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\DeleteShopCartItemListData;
use App\Packages\DataObjects\ShopCart\ShopCartShortData;

interface ShopCartModuleClientInterface
{
    public function getShopCart(): ShopCartData;
    public function getShortInfo(): ShopCartShortData;

    public function clearShopCart(?string $shopCartToken = null): void;

    public function addShopCartItems(AddShopCartItemListData $data): ShopCartData;

    public function deleteShopCartItems(DeleteShopCartItemListData $data): void;
}
