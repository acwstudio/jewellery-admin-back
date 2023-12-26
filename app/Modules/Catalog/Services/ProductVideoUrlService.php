<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductVideoUrl;
use App\Modules\Catalog\Repositories\ProductRepository;
use App\Modules\Catalog\Repositories\ProductVideoUrlRepository;
use App\Modules\Catalog\Support\Filters\ProductVideoUrlFilter;
use Illuminate\Support\Collection;

class ProductVideoUrlService
{
    public function __construct(
        private readonly ProductVideoUrlRepository $productVideoUrlRepository,
        private readonly ProductRepository $productRepository
    ) {
    }

    public function getProductVideoUrl(int $id): ?ProductVideoUrl
    {
        return $this->productVideoUrlRepository->getById($id);
    }

    public function getCollectionByFilter(ProductVideoUrlFilter $filter): Collection
    {
        return $this->productVideoUrlRepository->getCollectionByFilter($filter);
    }

    public function createProductVideoUrl(string $path, Product|int $product): ProductVideoUrl
    {
        if (is_int($product)) {
            $product = $this->productRepository->getById($product, true);
        }

        return $this->productVideoUrlRepository->create($path, $product);
    }

    public function updateProductVideoUrl(ProductVideoUrl|int $productVideoUrl, string $path): ProductVideoUrl
    {
        if (is_int($productVideoUrl)) {
            $productVideoUrl = $this->productVideoUrlRepository->getById($productVideoUrl, true);
        }

        $this->productVideoUrlRepository->update($productVideoUrl, $path);

        return $productVideoUrl->refresh();
    }

    public function deleteProductVideoUrl(ProductVideoUrl|int $productVideoUrl): void
    {
        if (is_int($productVideoUrl)) {
            $productVideoUrl = $this->productVideoUrlRepository->getById($productVideoUrl, true);
        }
        $this->productVideoUrlRepository->delete($productVideoUrl);
    }
}
