<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Repositories\FeatureRepository;
use App\Modules\Catalog\Repositories\ProductFeatureRepository;
use App\Modules\Catalog\Repositories\ProductRepository;
use App\Modules\Catalog\Support\Blueprints\ProductFeatureBlueprint;
use App\Modules\Catalog\Support\Filters\ProductFeatureFilter;
use Illuminate\Support\Collection;

class ProductFeatureService
{
    public function __construct(
        private readonly ProductFeatureRepository $productFeatureRepository,
        private readonly ProductRepository $productRepository,
        private readonly FeatureRepository $featureRepository,
    ) {
    }

    public function getProductFeature(string $id): ?ProductFeature
    {
        return $this->productFeatureRepository->getByUuid($id);
    }

    public function getProductFeatureCollectionByFilter(ProductFeatureFilter $filter): Collection
    {
        return $this->productFeatureRepository->getCollectionByFilter($filter);
    }

    public function createProductFeature(
        ProductFeatureBlueprint $productFeatureBlueprint,
        Product|int $product,
        Feature|int $feature,
        ProductFeature|string|null $parentProductFeature = null,
    ): ProductFeature {
        if (is_int($product)) {
            $product = $this->productRepository->getById($product, true);
        }

        if (is_int($feature)) {
            $feature = $this->featureRepository->getById($feature, true);
        }

        if (is_string($parentProductFeature)) {
            $parentProductFeature = $this->productFeatureRepository->getByUuidAndNullParentUuid(
                $parentProductFeature,
                true
            );
        }

        return $this->productFeatureRepository->create(
            $productFeatureBlueprint,
            $product,
            $feature,
            $parentProductFeature
        );
    }

    public function updateProductFeature(
        ProductFeature|string $productFeature,
        ProductFeatureBlueprint $productFeatureBlueprint,
        ProductFeature|string|null $parentProductFeature = null
    ): ProductFeature {
        if (is_string($productFeature)) {
            $productFeature = $this->productFeatureRepository->getByUuid($productFeature, true);
        }

        if (is_string($parentProductFeature)) {
            $parentProductFeature = $this->productFeatureRepository->getByUuidAndNullParentUuid(
                $parentProductFeature,
                true
            );
        }

        $this->productFeatureRepository->update($productFeature, $productFeatureBlueprint, $parentProductFeature);

        return $productFeature->refresh();
    }

    public function deleteProductFeature(ProductFeature|string $productFeature): void
    {
        if (is_string($productFeature)) {
            $productFeature = $this->productFeatureRepository->getByUuid($productFeature, true);
        }

        $this->productFeatureRepository->delete($productFeature);
    }
}
