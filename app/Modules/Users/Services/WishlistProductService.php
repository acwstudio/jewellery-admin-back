<?php

declare(strict_types=1);

namespace App\Modules\Users\Services;

use App\Modules\Users\Models\User;
use App\Modules\Users\Models\WishlistProduct;
use App\Modules\Users\Repositories\WishlistProductRepository;
use App\Modules\Users\Support\Pagination;
use App\Modules\Users\UseCases\GetWishlistShortList;
use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Users\Wishlist\WishlistShortData;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use RuntimeException;

class WishlistProductService
{
    public function __construct(
        private readonly WishlistProductRepository $wishlistProductRepository
    ) {
    }

    public function getWishlist(?User $user, Pagination $pagination): LengthAwarePaginator
    {
        if (!$user instanceof User) {
            throw new Exception('Требуется авторизация');
        }

        return $this->wishlistProductRepository->getList($user, $pagination);
    }

    /**
     * @throws RuntimeException
     */
    public function getWishlistShort(?User $user): WishlistShortData
    {
        if (!$user instanceof User) {
            throw new RuntimeException('Требуется авторизация');
        }

        return new WishlistShortData($this->wishlistProductRepository->getCount($user));
    }

    public function getWishlistProducts(?User $user): Collection
    {
        if (!$user instanceof User) {
            return new Collection();
        }

        return $this->wishlistProductRepository->getCollection($user);
    }

    public function createWishlistProduct(int $productId, User $user): WishlistProduct
    {
        return $this->wishlistProductRepository->create(
            $productId,
            $user
        );
    }

    public function deleteWishlistProduct(int $productId, User $user): void
    {
        $wishlistProduct = $this->wishlistProductRepository->getByProductId($user, $productId, true);
        $this->wishlistProductRepository->delete($wishlistProduct);
    }
}
