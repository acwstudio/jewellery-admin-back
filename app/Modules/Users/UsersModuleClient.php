<?php

declare(strict_types=1);

namespace App\Modules\Users;

use App\Modules\Users\Exceptions\OldPasswordNotValidException;
use App\Modules\Users\Models\User;
use App\Modules\Users\Services\AuthService;
use App\Modules\Users\Services\UserService;
use App\Modules\Users\Services\WishlistProductService;
use App\Modules\Users\Support\Blueprints\AuthBlueprint;
use App\Modules\Users\Support\Blueprints\OAuthBlueprint;
use App\Modules\Users\Support\Blueprints\UpdateUserBlueprint;
use App\Modules\Users\UseCases\ImportUsers;
use App\Modules\Users\UseCases\AddWishlistProduct;
use App\Modules\Users\UseCases\GetOrders;
use App\Modules\Users\UseCases\GetWishlistProductList;
use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Catalog\Product\ProductListData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Orders\Item\OrderItemData;
use App\Packages\DataObjects\Orders\Item\OrderItemListData;
use App\Packages\DataObjects\Orders\Order\OrderData;
use App\Packages\DataObjects\Orders\Order\OrderListData;
use App\Packages\DataObjects\Users\Auth\AuthLoginData;
use App\Packages\DataObjects\Users\Auth\AuthOauthData;
use App\Packages\DataObjects\Users\Order\GetOrderListData;
use App\Packages\DataObjects\Users\User\UpdateUserProfileData;
use App\Packages\DataObjects\Users\User\UserData;
use App\Packages\DataObjects\Users\User\UserProfileData;
use App\Packages\DataObjects\Users\Wishlist\GetWishlistData;
use App\Packages\DataObjects\Users\Wishlist\WishlistData;
use App\Packages\DataObjects\Users\Wishlist\WishlistShortData;
use App\Packages\Events\WishlistProductChanged;
use App\Packages\ModuleClients\UsersModuleClientInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class UsersModuleClient implements UsersModuleClientInterface
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly UserService $userService,
        private readonly WishlistProductService $wishlistProductService,
    ) {
    }

    public function getUser(): ?User
    {
        /** @var User|null $user */
        $user = auth('sanctum')->user();
        return $user;
    }

    public function login(AuthLoginData $data): UserData
    {
        /** @var User|null $user */
        $user = $this->authService->login(new AuthBlueprint(
            $data->phone,
            $data->otp_id,
            $data->otp_code,
            $data->email,
            $data->password
        ));

        if (!$user instanceof User) {
            throw new \Exception('Пользователь не найден');
        }

        Auth::login($user);
        return UserData::fromModel($user);
    }

    public function logout(): void
    {
        $user = $this->getUser();
        $user->tokens()->getQuery()->delete();
    }

    public function oauth(AuthOauthData $data): UserData
    {
        /** @var User|null $user */
        $user = $this->authService->oauth(new OAuthBlueprint(
            $data->token,
            $data->type
        ));

        if (!$user instanceof User) {
            throw new \Exception('Не удалось авторизоваться по OAuth');
        }

        Auth::login($user);
        return UserData::fromModel($user);
    }

    /**
     * @throws OldPasswordNotValidException
     */
    public function updateProfile(UpdateUserProfileData $data): UserProfileData
    {
        $user = $this->userService->updateUser(
            $this->getUser(),
            new UpdateUserBlueprint(
                $data->name,
                $data->surname,
                $data->patronymic,
                $data->sex,
                $data->birth_date,
                $data->email,
                $data->new_password,
                $data->old_password
            )
        );

        return UserProfileData::fromModel($user);
    }

    public function getProfile(): UserProfileData
    {
        return UserProfileData::fromModel($this->getUser());
    }

    public function getWishlist(GetWishlistData $data): WishlistData
    {
        /** @var \App\Packages\DataObjects\Catalog\Product\ProductListData|null $productListData */
        $productListData = App::call(GetWishlistProductList::class, [
            'user' => $this->getUser(),
            'data' => $data
        ]);

        return $this->createWishlistData($productListData);
    }

    public function getWishlistCollection(): Collection
    {
        return $this->wishlistProductService->getWishlistProducts($this->getUser());
    }

    public function createWishlistProduct(int $product_id): void
    {
        $user = $this->getUser();

        App::call(AddWishlistProduct::class, [
            'productId' => $product_id,
            'user' => $user
        ]);
        WishlistProductChanged::dispatch($this->getWishlistCollection());
    }

    public function getWishlistShort(): WishlistShortData
    {
        return $this->wishlistProductService->getWishlistShort($this->getUser());
    }

    public function deleteWishlistProduct(int $product_id): void
    {
        $user = $this->getUser();
        $this->wishlistProductService->deleteWishlistProduct($product_id, $user);
        WishlistProductChanged::dispatch($this->getWishlistCollection());
    }

    public function getOrder(int $id): OrderItemData
    {
        /** @var GetOrders $useCase */
        $useCase = App::make(GetOrders::class);
        return $useCase->get($this->getUser(), $id);
    }

    public function getOrders(GetOrderListData $data): OrderItemListData
    {
        /** @var GetOrders $useCase */
        $useCase = App::make(GetOrders::class);
        return $useCase->getList($this->getUser(), $data);
    }

    private function createWishlistData(?ProductListData $productListData): WishlistData
    {
        if (null === $productListData) {
            return new WishlistData(
                ProductData::collection([]),
                new PaginationData(1, 15, 0)
            );
        }

        return new WishlistData(
            $productListData->items,
            $productListData->pagination
        );
    }

    public function importUsers(?callable $onEach = null): void
    {
        $useCase = App::make(ImportUsers::class);
        $useCase($onEach);
    }
}
