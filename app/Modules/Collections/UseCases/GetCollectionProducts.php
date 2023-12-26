<?php

declare(strict_types=1);

namespace App\Modules\Collections\UseCases;

use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Catalog\Product\ProductListData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use Illuminate\Support\Collection;

class GetCollectionProducts
{
    public function __construct(
        private readonly CatalogModuleClientInterface $catalogModuleClient
    ) {
    }

    public function __invoke(CollectionModel $collection, ?int $limit = null): Collection
    {
        $collectionProducts = $collection->products()->allRelatedIds();
        if (!empty($limit)) {
            return $this->getActiveProducts($collectionProducts, $limit)->take($limit);
        }

        $products = $this->getProductListData($collectionProducts)->items;
        return new Collection($products->toArray());
    }

    private function getActiveProducts(Collection $collectionProducts, int $limit): Collection
    {
        $allProducts = new Collection();
        $chunkCollection = $collectionProducts->chunk($limit);
        foreach ($chunkCollection as $itemCollectionProducts) {
            $products = $this->getProductListData($itemCollectionProducts);
            $allProducts = $allProducts->merge($products->items->items());
            if ($allProducts->count() >= $limit) {
                break;
            }
        }

        return $allProducts;
    }

    private function getProductListData(Collection $collectionProducts): ProductListData
    {
        $data = new ProductGetListData(
            pagination: new PaginationData(
                page: 1,
                per_page: $collectionProducts->count()
            ),
            filter: new FilterProductData(
                in_stock: true,
                ids: $collectionProducts->toArray(),
                has_image: true,
                is_active: true,
            )
        );

        return $this->catalogModuleClient->getProducts($data);
    }
}
