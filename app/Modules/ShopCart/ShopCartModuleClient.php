<?php

declare(strict_types=1);

namespace App\Modules\ShopCart;

use App\Modules\ShopCart\Services\ShopCartItemService;
use App\Modules\ShopCart\Services\ShopCartService;
use App\Modules\ShopCart\Support\Blueprints\DeleteShopCartItemBlueprint;
use App\Modules\ShopCart\Support\Blueprints\ShopCartItemBlueprint;
use App\Modules\ShopCart\UseCases\GetShopCart;
use App\Modules\ShopCart\UseCases\GetShopCartShortInfo;
use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\AddShopCartItemData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\AddShopCartItemListData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\DeleteShopCartItemListData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartShortInfoData;
use App\Packages\DataObjects\ShopCart\ShopCartShortData;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

final class ShopCartModuleClient implements ShopCartModuleClientInterface
{
    public function __construct(
        private readonly ShopCartService $shopCartService,
        private readonly ShopCartItemService $shopCartItemService
    ) {
    }

    public function getShopCart(): ShopCartData
    {
        return App::call(GetShopCart::class, ['token' => $this->getShopCartToken()]);
    }

    public function getShortInfo(): ShopCartShortData
    {
        return App::call(GetShopCartShortInfo::class, ['token' => $this->getShopCartToken()]);
    }

    public function clearShopCart(?string $shopCartToken = null): void
    {
        $this->shopCartService->clearShopCart(
            $shopCartToken ?? $this->getShopCartToken()
        );
    }

    public function addShopCartItems(AddShopCartItemListData $data): ShopCartData
    {
        $shopCart = $this->shopCartService->getOrCreateShopCart(
            $this->getShopCartToken()
        );

        /** @var AddShopCartItemData $item */
        foreach ($data->items as $item) {
            $this->shopCartItemService->addShopCartItem(
                new ShopCartItemBlueprint(
                    $item->count,
                    $item->product_id,
                    $item->product_offer_id,
                    $item->selected,
                    $shopCart->token
                )
            );
        }

        return App::call(GetShopCart::class, ['token' => $shopCart->token]);
    }


    public function deleteShopCartItems(DeleteShopCartItemListData $data): void
    {
        $shopCartToken = $this->getShopCartToken();
        foreach ($data->product_offer_ids as $productOfferId) {
            $this->shopCartItemService->deleteShopCartItem(
                new DeleteShopCartItemBlueprint(
                    $productOfferId,
                    $shopCartToken
                )
            );
        }
    }

    private function getShopCartToken(): ?string
    {
        $token = request()->header('shop-cart-token');

        if (!empty($token) && Str::isUuid($token)) {
            return (string)$token;
        }

        return null;
    }
}
