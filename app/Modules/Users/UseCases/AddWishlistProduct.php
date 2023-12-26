<?php

declare(strict_types=1);

namespace App\Modules\Users\UseCases;

use App\Modules\Users\Models\User;
use App\Modules\Users\Models\WishlistProduct;
use App\Modules\Users\Services\WishlistProductService;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;

class AddWishlistProduct
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly WishlistProductService $wishlistProductService
    ) {
    }

    public function __invoke(int $productId, User $user): WishlistProduct
    {
        $productData = $this->getProductData($productId);

        if (!$productData instanceof ProductData) {
            throw new \Exception('Продукт не найден');
        }

        return $this->wishlistProductService->createWishlistProduct(
            $productData->id,
            $user
        );
    }

    private function getProductData(int $productId): ?ProductData
    {
        $data = new ProductGetListData(
            pagination: new PaginationData(1, 1),
            filter: new FilterProductData(ids: [$productId])
        );

        $productListData = $this->catalogModuleClient->getProducts($data);
        return $productListData->items->first();
    }
}
