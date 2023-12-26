<?php

declare(strict_types=1);

namespace App\Packages\Facades;

use App\Packages\DataObjects\Orders\Order\OrderData;
use App\Packages\DataObjects\Orders\Order\OrderListData;
use App\Packages\DataObjects\Users\Auth\AuthLoginData;
use App\Packages\DataObjects\Users\Auth\AuthOauthData;
use App\Packages\DataObjects\Users\User\UpdateUserProfileData;
use App\Packages\DataObjects\Users\User\UserData;
use App\Packages\DataObjects\Users\User\UserProfileData;
use App\Packages\DataObjects\Users\Wishlist\GetWishlistData;
use App\Packages\DataObjects\Users\Wishlist\WishlistData;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Modules\Users\Models\User|null getUser()
 * @method static UserData login(AuthLoginData $data)
 * @method static logout()
 * @method static UserData oauth(AuthOauthData $data)
 * @method static UserProfileData updateProfile(UpdateUserProfileData $data)
 * @method static UserProfileData getProfile()
 * @method static WishlistData getWishlist(GetWishlistData $data)
 * @method static Collection getWishlistCollection()
 * @method static createWishlistProduct(int $product_id)
 * @method static deleteWishlistProduct(int $product_id)
 * @method static OrderData getOrder(int $id)
 * @method static OrderListData getOrders()
 * @method static importUsers(callable|null $onEach = null)
 */
class User extends Facade
{
    protected static function getFacadeAccessor()
    {
        return UsersModuleClientInterface::class;
    }
}
