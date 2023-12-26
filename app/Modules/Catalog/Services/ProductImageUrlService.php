<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductImageUrl;
use App\Modules\Catalog\Repositories\ProductImageUrlRepository;
use App\Modules\Catalog\Repositories\ProductRepository;
use App\Modules\Catalog\Support\Blueprints\ProductImageUrlBlueprint;
use App\Modules\Catalog\Support\Filters\ProductImageUrlFilter;
use Illuminate\Support\Collection;

class ProductImageUrlService
{
    public function __construct(
        private readonly ProductImageUrlRepository $productImageUrlRepository,
        private readonly ProductRepository $productRepository
    ) {
    }

    public function getProductImageUrl(int $id): ?ProductImageUrl
    {
        return $this->productImageUrlRepository->getById($id);
    }

    public function getFeatureCollectionByFilter(ProductImageUrlFilter $filter): Collection
    {
        return $this->productImageUrlRepository->getCollectionByFilter($filter);
    }

    public function createProductImageUrl(
        ProductImageUrlBlueprint $blueprint,
        Product|int $product
    ): ProductImageUrl {
        if (is_int($product)) {
            $product = $this->productRepository->getById($product, true);
        }

        return $this->productImageUrlRepository->create($blueprint, $product);
    }

    public function updateProductImageUrl(
        ProductImageUrl|int $productImageUrl,
        ProductImageUrlBlueprint $blueprint
    ): ProductImageUrl {
        if (is_int($productImageUrl)) {
            $productImageUrl = $this->productImageUrlRepository->getById($productImageUrl, true);
        }

        $this->productImageUrlRepository->update($productImageUrl, $blueprint);

        return $productImageUrl->refresh();
    }

    public function deleteProductImageUrl(ProductImageUrl|int $productImageUrl): void
    {
        if (is_int($productImageUrl)) {
            $productImageUrl = $this->productImageUrlRepository->getById($productImageUrl, true);
        }
        $this->productImageUrlRepository->delete($productImageUrl);
    }
}
