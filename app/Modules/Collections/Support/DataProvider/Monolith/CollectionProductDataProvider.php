<?php

declare(strict_types=1);

namespace App\Modules\Collections\Support\DataProvider\Monolith;

use App\Modules\Collections\Services\CollectionService;
use App\Modules\Collections\Support\DataProvider\DataProviderInterface;
use App\Modules\Collections\Support\Pagination;
use App\Packages\DataObjects\Catalog\Filter\FilterProductData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\DataObjects\Common\Pagination\PaginationData;
use App\Packages\ModuleClients\CatalogModuleClientInterface;
use App\Packages\ModuleClients\MonolithModuleClientInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class CollectionProductDataProvider implements DataProviderInterface
{
    public function __construct(
        private readonly MonolithModuleClientInterface $monolithModuleClient,
        private readonly CatalogModuleClientInterface $catalogModuleClient,
        private readonly CollectionService $collectionService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getRawData(): iterable
    {
        $perPage = config('collections.import.products.per_page', 100);
        $productResponse = [];
        $isRepeat = true;
        $page = 1;

        while ($isRepeat) {
            $collections = $this->collectionService->getAllCollections(new Pagination($page, $perPage));
            $this->getResponse($productResponse, $collections);

            $this->logger->debug('CollectionProductDataProvider::getRawData::while', [
                'page' => $collections->currentPage(),
                'perPage' => $collections->perPage(),
                'lastPage' => $collections->lastPage(),
                'products' => $collections->total(),
                'responseCount' => count($productResponse)
            ]);
            $isRepeat = $collections->lastPage() > $collections->currentPage();
            $page++;
        }

        return $productResponse;
    }

    private function getResponse(array &$productResponse, LengthAwarePaginator $paginator): void
    {
        $perPage = config('collections.import.products.per_page', 100);
        /** @var \App\Modules\Collections\Models\Collection $collection */
        foreach ($paginator->items() as $collection) {
            try {
                $monolithSkuProducts = $this->getMonolithSkuProducts($collection->name);
                $chunkMonolithProducts = array_chunk($monolithSkuProducts, $perPage);

                $catalogProductIds = [];
                $catalogProductCategoryIds = [];
                foreach ($chunkMonolithProducts as $groupMonolithProducts) {
                    $catalogProducts = $this->getCatalogProducts($groupMonolithProducts);
                    $catalogProductIds = $this->getCatalogProductIds($catalogProducts, $catalogProductIds);
                    $catalogProductCategoryIds = $this->getProductCategoryIds(
                        $catalogProducts,
                        $catalogProductCategoryIds
                    );
                }

                $this->logger->debug('CollectionProductDataProvider::getResponse', [
                    'collection_id' => $collection->getKey(),
                    'monolith' => count($monolithSkuProducts),
                    'product_ids' => count($catalogProductIds)
                ]);

                $productResponse[] = [
                    'collection_id' => $collection->getKey(),
                    'product_ids' => $catalogProductIds,
                    'category_ids' => $catalogProductCategoryIds
                ];
            } catch (\Throwable $e) {
                $this->logger->error(
                    "Collection Products with collectionId: {$collection->id} error",
                    ['error' => $e->getMessage()]
                );
            }
        }
    }

    private function getMonolithSkuProducts(string $name): array
    {
        $response = $this->monolithModuleClient->getCollectionProducts($name);
        $monolithProducts = (array)$response;

        if (!empty($monolithProducts['error'])) {
            throw new \Exception(strval($monolithProducts['error']));
        }

        if (empty($monolithProducts['sku_ids']) || !is_array($monolithProducts['sku_ids'])) {
            throw new \Exception('Empty Monolith products');
        }

        return $monolithProducts['sku_ids'];
    }

    private function getCatalogProducts(array $productSku): Collection
    {
        $response = $this->catalogModuleClient->getProducts(new ProductGetListData(
            pagination: new PaginationData(1, count($productSku)),
            filter: new FilterProductData(sku: implode(',', $productSku))
        ));

        return new Collection($response->items->items());
    }

    private function getCatalogProductIds(Collection $products, array $catalogProductIds = []): array
    {
        $ids = $products->pluck('id')->toArray();

        $catalogProductIds = array_merge($catalogProductIds, $ids);

        return array_unique($catalogProductIds);
    }

    private function getProductCategoryIds(Collection $products, array $categoryIds = []): array
    {
        /** @var \App\Packages\DataObjects\Catalog\Product\ProductData $productData */
        foreach ($products as $productData) {
            $categoryIds = array_merge($categoryIds, $productData->categories);
        }

        return array_unique($categoryIds);
    }
}
