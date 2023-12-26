<?php

declare(strict_types=1);

namespace App\Packages\ModuleClients;

use App\Modules\Users\Models\User;
use App\Packages\DataObjects\Orders\Item\OrderItemData;
use App\Packages\DataObjects\Orders\Item\OrderItemListData;
use App\Packages\DataObjects\Users\Auth\AuthLoginData;
use App\Packages\DataObjects\Users\Auth\AuthOauthData;
use App\Packages\DataObjects\Users\Order\GetOrderListData;
use App\Packages\DataObjects\Users\User\UpdateUserProfileData;
use App\Packages\DataObjects\Users\User\UserData;
use App\Packages\DataObjects\Users\User\UserProfileData;
use App\Packages\DataObjects\Users\Wishlist\GetWishlistData;
use App\Packages\DataObjects\Users\Wishlist\WishlistData;
use App\Packages\DataObjects\Users\Wishlist\WishlistShortData;
use Illuminate\Support\Collection;

interface UsersModuleClientInterface
{
    public function getUser(): ?User;

    public function login(AuthLoginData $data): UserData;

    public function logout(): void;

    public function oauth(AuthOauthData $data): UserData;

    public function updateProfile(UpdateUserProfileData $data): UserProfileData;

    public function getProfile(): UserProfileData;

    public function getWishlist(GetWishlistData $data): WishlistData;

    public function getWishlistShort(): WishlistShortData;

    public function getWishlistCollection(): Collection;

    public function createWishlistProduct(int $product_id): void;

    public function deleteWishlistProduct(int $product_id): void;

    public function getOrder(int $id): OrderItemData;

    public function getOrders(GetOrderListData $data): OrderItemListData;

    public function importUsers(?callable $onEach = null): void;
}
