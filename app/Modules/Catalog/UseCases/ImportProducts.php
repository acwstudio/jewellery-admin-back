<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Models\ProductImageUrl;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Services\CategoryService;
use App\Modules\Catalog\Services\FeatureService;
use App\Modules\Catalog\Services\Import\ProductImportService;
use App\Modules\Catalog\Services\ProductFeatureService;
use App\Modules\Catalog\Services\ProductImageUrlService;
use App\Modules\Catalog\Services\ProductOfferService;
use App\Modules\Catalog\Services\ProductService;
use App\Modules\Catalog\Services\ProductVideoUrlService;
use App\Modules\Catalog\Support\Blueprints\CategoryBlueprint;
use App\Modules\Catalog\Support\Blueprints\FeatureBlueprint;
use App\Modules\Catalog\Support\Blueprints\ProductBlueprint;
use App\Modules\Catalog\Support\Blueprints\ProductFeatureBlueprint;
use App\Modules\Catalog\Support\Blueprints\ProductImageUrlBlueprint;
use App\Modules\Catalog\Support\Blueprints\ProductOfferBlueprint;
use App\Modules\Catalog\Support\Filters\CategoryFilter;
use App\Modules\Catalog\Support\Filters\FeatureFilter;
use App\Modules\Catalog\Support\Filters\ProductFeatureFilter;
use App\Modules\Catalog\Support\Filters\ProductImageUrlFilter;
use App\Modules\Catalog\Support\Filters\ProductVideoUrlFilter;
use App\Modules\Catalog\Support\SlugGenerator;
use App\Packages\DataObjects\Catalog\Product\ImageUrl\ImportProductImageUrlData;
use App\Packages\DataObjects\Catalog\Product\ImportProductData;
use App\Packages\DataObjects\Catalog\Product\VideoUrl\ImportProductVideoUrlData;
use App\Packages\DataObjects\Catalog\ProductFeature\ImportProductFeatureData;
use App\Packages\DataObjects\Catalog\ProductOffer\ImportProductOfferData;
use App\Packages\Events\Sync\ProductsImported;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

class ImportProducts
{
    public function __construct(
        private readonly ProductImportService $productImportService,
        private readonly LoggerInterface $logger,
        private readonly ProductService $productService,
        private readonly CategoryService $categoryService,
        private readonly ProductOfferService $productOfferService,
        private readonly ProductImageUrlService $productImageUrlService,
        private readonly ProductVideoUrlService $productVideoUrlService,
        private readonly ProductFeatureService $productFeatureService,
        private readonly FeatureService $featureService,
        private readonly SlugGenerator $slugGenerator,
        private array $mappingInserts = []
    ) {
    }

    public function __invoke(?callable $onEach = null): void
    {
        $this->mappingInserts = $this->getMappingArray(FeatureTypeEnum::INSERT);

        $this->productImportService->import(function (ImportProductData $data) use ($onEach) {
            try {
                DB::transaction(function () use ($data) {
                    $this->upsertProduct($data);
                });
            } catch (\Throwable $e) {
                $this->logger->error(
                    "Product with extID: $data->external_id import error",
                    ['exception' => $e]
                );
            }

            if (null !== $onEach) {
                call_user_func($onEach);
            }
        });

        ProductsImported::dispatch();
    }

    private function upsertProduct(ImportProductData $data): void
    {
        $product = $this->productService->getProductByExternalId($data->external_id);

        $categories = $this->getCategories($data);

        $productName = $data->siteName ?? $data->name;
        $blueprint = new ProductBlueprint(
            $data->sku,
            $productName,
            $productName,
            $data->description,
            'Россия',
            0,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            $data->is_active,
            null,
            null,
            $data->external_id,
            $productName,
            $data->description,
            $this->slugGenerator->createForProduct($productName, $data->sku)
        );

        if (!$product instanceof Product) {
            $product = $this->productService->createProduct(
                $blueprint,
                $categories->pluck('id')->toArray()
            );
        } else {
            $this->productService->updateProduct(
                $product,
                $blueprint,
                $categories->pluck('id')->toArray()
            );
            $product->refresh();
        }

        $this->upsertProductFeature($product, $data->productFeatures);
        $this->upsertProductOffer($product, $data->productOffers);
        $this->upsertProductImageUrl($product, $data->productImageUrls);
        $this->upsertProductVideoUrl($product, $data->productVideoUrls);
        $product->updateInScout();
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

    /**
     * @param Product $product
     * @param Collection<ImportProductOfferData> $productOffers
     * @return void
     */
    private function upsertProductOffer(Product $product, Collection $productOffers): void
    {
        foreach ($productOffers as $productOfferData) {
            $this->getOrCreateProductOffer(
                $product,
                $productOfferData->size,
                $productOfferData->weight
            );
        }
    }

    /**
     * @param Product $product
     * @param Collection<ImportProductImageUrlData> $productImageUrls
     * @return void
     */
    private function upsertProductImageUrl(Product $product, Collection $productImageUrls): void
    {
        foreach ($productImageUrls as $productImageUrlData) {
            $productImageUrl = $this->productImageUrlService->getFeatureCollectionByFilter(
                new ProductImageUrlFilter(product_id: $product->id, path: $productImageUrlData->path)
            )->first();

            if (!$productImageUrl instanceof ProductImageUrl) {
                $this->productImageUrlService->createProductImageUrl(
                    new ProductImageUrlBlueprint($productImageUrlData->path, $productImageUrlData->is_main),
                    $product
                );
            }
        }
    }

    private function upsertProductVideoUrl(Product $product, Collection $productVideoUrls): void
    {
        /** @var ImportProductVideoUrlData $productVideoUrlData */
        foreach ($productVideoUrls as $productVideoUrlData) {
            $productVideoUrl = $this->productVideoUrlService->getCollectionByFilter(
                new ProductVideoUrlFilter(product_id: $product->id, path: $productVideoUrlData->path)
            )->first();

            if (null === $productVideoUrl) {
                $this->productVideoUrlService->createProductVideoUrl(
                    $productVideoUrlData->path,
                    $product
                );
            }
        }
    }

    private function getCategories(ImportProductData $data): Collection
    {
        $categories = collect();
        if (null === $data->productCategory) {
            return $categories;
        }

        $mainCategory = $this->getOrCreateCategoryByTitle($data->productCategory);
        $categories->add($mainCategory);

        foreach ($data->productSubCategories as $subCategory) {
            $category = $this->getOrCreateCategoryByTitle($subCategory, $mainCategory);
            $categories->add($category);
        }

        return $categories;
    }

    private function getOrCreateProductOffer(Product $product, ?string $size, ?string $weight): ProductOffer
    {
        /** @var ProductOffer|null $productOffer */
        $productOffer = $product->productOffers()->getQuery()
            ->where('size', '=', $size)
            ->where('weight', '=', $weight)
            ->first();

        if ($productOffer instanceof ProductOffer) {
            return $productOffer;
        }

        return $this->productOfferService->createProductOffer(
            new ProductOfferBlueprint($size, $weight),
            $product
        );
    }

    private function getOrCreateProductFeature(
        Product $product,
        ImportProductFeatureData $data,
        ?ProductFeature $parent = null
    ): ProductFeature {
        $feature = $this->getOrCreateFeature($data->type, $data->typeValue);

        $value = $data->value;
        if (FeatureTypeEnum::INSERT === $feature->type && $feature->value !== $data->typeValue) {
            $value = $data->typeValue;
        }

        $productFeature = $this->productFeatureService->getProductFeatureCollectionByFilter(
            new ProductFeatureFilter(
                product_id: $product->id,
                feature_id: $feature->id,
                value: $value,
                parent_uuid: $parent?->uuid,
                is_main: $data->is_main
            )
        )->first();

        if ($productFeature instanceof ProductFeature) {
            return $productFeature;
        }

        return $this->productFeatureService->createProductFeature(
            new ProductFeatureBlueprint(
                $value,
                $data->is_main
            ),
            $product,
            $feature,
            $parent
        );
    }

    private function getOrCreateCategoryByTitle(string $title, ?Category $parentCategory = null): Category
    {
        $category = $this->categoryService->getCategories(
            new CategoryFilter(title: $title, parent_id: $parentCategory?->id ?? null)
        )->first();

        if ($category instanceof Category) {
            return $category;
        }

        return $this->categoryService->createCategory(
            categoryBlueprint: new CategoryBlueprint($title, $title, ''),
            parent: $parentCategory
        );
    }

    private function getOrCreateFeature(FeatureTypeEnum $type, string $value): Feature
    {
        $value = $this->getFeatureAltValue($type, $value) ?? Str::ucfirst($value);

        $feature = $this->featureService->getFeatureCollectionByFilter(
            new FeatureFilter(type: $type, slug: $type->getSlug($value))
        )->first();

        if ($feature instanceof Feature) {
            return $feature;
        }

        return $this->featureService->createFeature(
            new FeatureBlueprint(
                type: $type,
                value: $value
            )
        );
    }

    private function getFeatureAltValue(FeatureTypeEnum $type, string $value): ?string
    {
        $value = Str::lower($value);
        if (FeatureTypeEnum::INSERT === $type) {
            return $this->mappingInserts[$value] ?? null;
        }

        return null;
    }

    private function getMappingArray(FeatureTypeEnum $type): array
    {
        if (FeatureTypeEnum::INSERT === $type) {
            $mapping = json_decode(file_get_contents(resource_path('mapping/inserts.json')), true);
            return $this->convertMapping($mapping);
        }

        return [];
    }

    private function convertMapping(array $mapping): array
    {
        $convert = [];

        foreach ($mapping as $item) {
            $key = Str::lower($item['key']);
            $convert[$key] = Str::ucfirst($item['value']);
        }

        return $convert;
    }
}
