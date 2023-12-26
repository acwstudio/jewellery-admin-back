<?php

declare(strict_types=1);

namespace App\Modules\ShopCart\Services;

use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Repositories\ShopCartRepository;
use App\Packages\ModuleClients\UsersModuleClientInterface;

class ShopCartService
{
    public function __construct(
        private readonly ShopCartRepository $shopCartRepository,
        private readonly UsersModuleClientInterface $usersModuleClient
    ) {
    }

    public function getShopCart(int $id): ?ShopCart
    {
        return $this->shopCartRepository->getById($id);
    }

    public function getOrCreateShopCart(?string $shopCartToken = null): ShopCart
    {
        $userId = $this->usersModuleClient->getUser()?->user_id;
        return $this->shopCartRepository->getOrCreate($userId, $shopCartToken);
    }

    public function clearShopCart(?string $shopCartToken = null): void
    {
        $shopCart = $this->getOrCreateShopCart($shopCartToken);
        $shopCart->items()->getQuery()->delete();

        $shopCart->save();
    }
}
