<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Services\CategoryService;
use App\Modules\Catalog\Services\FeatureService;
use App\Modules\Catalog\Services\ProductFilterImportService;
use App\Modules\Catalog\Services\ProductFeatureService;
use App\Modules\Catalog\Services\ProductService;
use App\Modules\Catalog\Support\Blueprints\CategoryBlueprint;
use App\Modules\Catalog\Support\Blueprints\FeatureBlueprint;
use App\Modules\Catalog\Support\Blueprints\ProductFeatureBlueprint;
use App\Modules\Catalog\Support\Filters\CategoryFilter;
use App\Modules\Catalog\Support\Filters\FeatureFilter;
use App\Modules\Catalog\Support\Filters\ProductFeatureFilter;
use App\Modules\Catalog\Support\SlugGenerator;
use App\Packages\DataObjects\Catalog\Product\MonolithProductFilterData;
use App\Packages\DataObjects\Catalog\ProductFeature\ImportProductFeatureData;
use App\Packages\Events\Sync\ProductFiltersImported;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class ImportProductFilter
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly ProductFilterImportService $productFilterImportService,
        private readonly LoggerInterface $logger,
        private readonly CategoryService $categoryService,
        private readonly ProductFeatureService $productFeatureService,
        private readonly FeatureService $featureService,
        private readonly SlugGenerator $slugGenerator,
    ) {
    }

    public function __invoke(?callable $onEach = null): void
    {
        try {
            $dataList = $this->productFilterImportService->import();
        } catch (\Throwable $e) {
            $this->logger->error(
                "Get product filters from Monolith error",
                ['exception' => $e]
            );
            return;
        }

        /** @var MonolithProductFilterData $data */
        foreach ($dataList as $data) {
            try {
                DB::transaction(function () use ($data) {
                    $this->updateProduct($data);
                });
            } catch (\Throwable $e) {
                $this->logger->error(
                    "Product filters with productSKU: $data->sku import error",
                    ['exception' => $e]
                );
            }

            if (null !== $onEach) {
                call_user_func($onEach);
            }
        }

        ProductFiltersImported::dispatch();
    }

    private function updateProduct(MonolithProductFilterData $data): void
    {
        if (empty($data->parentCategory)) {
            return;
        }

        $product = $this->productService->getProductBySku($data->sku);
        $product->update([
            'is_active' => $data->isActive,
            'name' => $data->name ?? $product->name,
            'description' => $data->description ?? $product->description,
            'slug' => $this->slugGenerator->createForProduct($data->name ?? $product->name, $product->sku)
        ]);
        $this->upsertProductCategory($product, $data->parentCategory, $data->childCategories);
        $this->upsertProductFeature($product, $data->productFeatures);
    }

    private function upsertProductCategory(Product $product, string $parentName, array $childNames): void
    {
        if (empty($parentName)) {
            return;
        }

        $parentCategory = $this->getOrCreateCategoryByTitle($parentName);

        $allCategories = [$parentCategory->id];
        foreach ($childNames as $name) {
            $allCategories[] = $this->getOrCreateCategoryByTitle($name, $parentCategory)->id;
        }

        $product->categories()->sync($allCategories);
    }

    /**
     * @param Product $product
     * @param Collection<ImportProductFeatureData> $productFeatures
     * @return void
     */
    private function upsertProductFeature(Product $product, Collection $productFeatures): void
    {
        foreach ($productFeatures as $productFeature) {
            $parent = $this->getOrCreateProductFeature($product, $productFeature);
            /** @var ImportProductFeatureData $child */
            foreach ($productFeature->children as $child) {
                $this->getOrCreateProductFeature($product, $child, $parent);
            }
        }
    }

    private function getOrCreateProductFeature(
        Product $product,
        ImportProductFeatureData $data,
        ?ProductFeature $parent = null
    ): ProductFeature {
        $feature = $this->getOrCreateFeature($data->type, $data->typeValue);

        $productFeature = $this->productFeatureService->getProductFeatureCollectionByFilter(
            new ProductFeatureFilter(
                product_id: $product->id,
                feature_id: $feature->id,
                value: $data->value,
                parent_uuid: $parent?->uuid,
                is_main: $data->is_main
            )
        )->first();

        if ($productFeature instanceof ProductFeature) {
            return $productFeature;
        }

        return $this->productFeatureService->createProductFeature(
            new ProductFeatureBlueprint(
                $data->value,
                $data->is_main
            ),
            $product,
            $feature,
            $parent
        );
    }

    private function getOrCreateCategoryByTitle(string $title, ?Category $parent = null): Category
    {
        $category = $this->categoryService->getCategories(
            new CategoryFilter(title: $title, parent_id: $parent?->id)
        )->first();

        if ($category instanceof Category) {
            return $category;
        }

        return $this->categoryService->createCategory(new CategoryBlueprint($title, $title, ''), $parent);
    }

    private function getOrCreateFeature(FeatureTypeEnum $type, string $value): Feature
    {
        $feature = $this->featureService->getFeatureCollectionByFilter(
            new FeatureFilter(type: $type, slug: $type->getSlug($value))
        )->first();

        if ($feature instanceof Feature) {
            return $feature;
        }

        return $this->featureService->createFeature(new FeatureBlueprint($type, $value));
    }
}
