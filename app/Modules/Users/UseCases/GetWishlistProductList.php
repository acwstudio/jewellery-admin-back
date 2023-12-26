<?php

declare(strict_types=1);

namespace App\Modules\Users\UseCases;

use App\Modules\Users\Models\User;
use App\Modules\Users\Services\WishlistProductService;
use App\Modules\Users\Support\Pagination;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductListData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\DataObjects\Users\Wishlist\GetWishlistData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;

class GetWishlistProductList
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly WishlistProductService $wishlistProductService
    ) {
    }

    public function __invoke(?User $user, GetWishlistData $data): ?ProductListData
    {
        if (!$user instanceof User) {
            return null;
        }

        $wishlist = $this->wishlistProductService->getWishlist(
            $user,
            new Pagination($data->pagination?->page, $data->pagination?->per_page)
        );

        if ($wishlist->total() === 0) {
            return null;
        }

        $productListData = $this->getProductListData(new Collection($wishlist->items()));

        return new ProductListData(
            $productListData->items,
            new PaginationData(
                $wishlist->currentPage(),
                $wishlist->perPage(),
                $wishlist->total()
            )
        );
    }

    private function getProductListData(Collection $wishlistProducts): ProductListData
    {
        $data = new ProductGetListData(
            pagination: new PaginationData(1, $wishlistProducts->count()),
            filter: new FilterProductData(ids: $wishlistProducts->pluck('product_id')->toArray())
        );

        return $this->catalogModuleClient->getProducts($data);
    }
}
