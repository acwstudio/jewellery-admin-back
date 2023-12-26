<?php

declare(strict_types=1);

namespace App\Modules\ShopCart\Services;

use App\Modules\ShopCart\Models\ShopCartItem;
use App\Modules\ShopCart\Repositories\ShopCartItemRepository;
use App\Modules\ShopCart\Repositories\ShopCartRepository;
use App\Modules\ShopCart\Support\Blueprints\DeleteShopCartItemBlueprint;
use App\Modules\ShopCart\Support\Blueprints\ShopCartItemBlueprint;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Exception;
use RuntimeException;

class ShopCartItemService
{
    public function __construct(
        private readonly ShopCartItemRepository $shopCartItemRepository,
        private readonly ShopCartRepository $shopCartRepository,
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly UsersModuleClientInterface $usersModuleClient
    ) {
    }

    /**
     * @throws Exception
     */
    public function addShopCartItem(ShopCartItemBlueprint $shopCartItemBlueprint): ShopCartItem
    {
        $userId = $this->usersModuleClient->getUser()?->user_id;

        $shopCart = $this->shopCartRepository->getOrCreate($userId, $shopCartItemBlueprint->shop_cart_token);
        $productOfferData = $this->catalogModuleClient->getProductOffer($shopCartItemBlueprint->product_offer_id);

        if (!config('app.debug')) {
            $this->checkProductOfferStock($productOfferData->id, $shopCartItemBlueprint->count);
        }

        $blueprint = new ShopCartItemBlueprint(
            $shopCartItemBlueprint->count,
            $productOfferData->product_id,
            $productOfferData->id,
            $shopCartItemBlueprint->selected,
            $userId
        );

        return $this->shopCartItemRepository->createOrUpdate($blueprint, $shopCart);
    }

    public function deleteShopCartItem(DeleteShopCartItemBlueprint $deleteShopCartItemBlueprint): void
    {
        $userId = $this->usersModuleClient->getUser()?->user_id;

        $shopCart = $this->shopCartRepository->getOrCreate($userId, $deleteShopCartItemBlueprint->shop_cart_token);

        /** @var ShopCartItem $shopCartItem */
        $shopCartItem = $shopCart->items()
            ->getQuery()
            ->where('product_offer_id', '=', $deleteShopCartItemBlueprint->product_offer_id)
            ->firstOrFail();

        $this->shopCartItemRepository->delete($shopCartItem);
    }

    /**
     * @throws Exception
     */
    private function checkProductOfferStock(int $productOfferId, int $count): void
    {
        $productOfferStockAvailable = $this->catalogModuleClient->getProductOfferStockAvailable($productOfferId);

        if ($productOfferStockAvailable >= $count) {
            return;
        }

        throw new RuntimeException('Offer stock exceeded. Total available ' . $productOfferStockAvailable);
    }
}
