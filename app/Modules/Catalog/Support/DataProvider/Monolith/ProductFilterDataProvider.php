<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\DataProvider\Monolith;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Services\ProductService;
use App\Modules\Catalog\Support\DataProvider\DataProviderInterface;
use App\Modules\Catalog\Support\Pagination;
use App\Packages\ModuleClients\MonolithModuleClientInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Psr\Log\LoggerInterface;

class ProductFilterDataProvider implements DataProviderInterface
{
    public function __construct(
        private readonly MonolithModuleClientInterface $monolithModuleClient,
        private readonly ProductService $productService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getRawData(): iterable
    {
        $perPage = config('catalog.import.product_filter.per_page', 100);

        $productResponse = [];
        $products = $this->productService->getAllProductsByPagination(new Pagination(1, $perPage));
        $this->getResponse($productResponse, $this->getSkuArray($products));

        $this->logger->debug('ProductFilterDataProvider::getRawData', [
            'page' => $products->currentPage(),
            'perPage' => $products->perPage(),
            'products' => $products->total(),
            'responseCount' => count($productResponse)
        ]);

        while ($products->lastPage() > $products->currentPage()) {
            $page = $products->currentPage() + 1;
            $products = $this->productService->getAllProductsByPagination(new Pagination($page, $perPage));
            $this->getResponse($productResponse, $this->getSkuArray($products));
            $this->logger->debug('ProductFilterDataProvider::getRawData::while', [
                'page' => $products->currentPage(),
                'perPage' => $products->perPage(),
                'products' => $products->total(),
                'responseCount' => count($productResponse)
            ]);
        }

        return $productResponse;
    }

    private function getResponse(array &$productResponse, array $skuArray = []): void
    {
        $response = $this->monolithModuleClient->getProductFilters($skuArray);
        $productResponse = array_merge($productResponse, (array)$response);
    }

    private function getSkuArray(LengthAwarePaginator $paginator): array
    {
        return array_map(
            fn (Product $product) => $product->sku,
            $paginator->items()
        );
    }
}
