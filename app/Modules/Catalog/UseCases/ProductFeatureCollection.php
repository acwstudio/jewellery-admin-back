<?php

declare(strict_types=1);

namespace App\Modules\Catalog\UseCases;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Services\FeatureService;
use App\Modules\Catalog\Services\ProductFeatureService;
use App\Modules\Catalog\Services\ProductService;
use App\Modules\Catalog\Support\Blueprints\FeatureBlueprint;
use App\Modules\Catalog\Support\Blueprints\ProductFeatureBlueprint;
use App\Modules\Catalog\Support\Filters\FeatureFilter;
use App\Modules\Catalog\Support\Filters\ProductFeatureFilter;
use App\Packages\ModuleClients\CollectionModuleClientInterface;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

class ProductFeatureCollection
{
    public function __construct(
        private readonly CollectionModuleClientInterface $collectionModuleClient,
        private readonly ProductService $productService,
        private readonly FeatureService $featureService,
        private readonly ProductFeatureService $productFeatureService,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(int $collectionId): void
    {
        try {
            $collection = $this->collectionModuleClient->getCollectionShort($collectionId);

            if (null === $collection) {
                throw new \Exception('Collection not found');
            }

            if (count($collection->products) === 0) {
                throw new \Exception('Collection not exist products');
            }

            $products = $this->productService->getProductByIds($collection->products);

            /** @var Product $product */
            foreach ($products as $product) {
                $this->createProductFeatureCollection($product, $collection->name);
                $product->updateInScout();
            }

            $this->logger->info('[ProductFeatureCollection] Successful', [
                'collectionId' => $collectionId
            ]);
        } catch (\Throwable $e) {
            $this->logger->error('[ProductFeatureCollection] Error', [
                'collectionId' => $collectionId,
                'message' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine()
            ]);
        }
    }

    private function createProductFeatureCollection(Product $product, string $collectionName): void
    {
        $featureCollection = $this->getOrCreateFeatureCollection($collectionName);

        /** @var ProductFeature|null $productFeature */
        $productFeature = $this->productFeatureService->getProductFeatureCollectionByFilter(
            new ProductFeatureFilter(
                product_id: $product->id,
                feature_id: $featureCollection->id
            )
        )->first();

        if (null !== $productFeature) {
            return;
        }

        $this->productFeatureService->createProductFeature(
            new ProductFeatureBlueprint(),
            $product,
            $featureCollection
        );
    }

    private function getOrCreateFeatureCollection(string $value): Feature
    {
        $value = Str::ucfirst($value);
        $type = FeatureTypeEnum::COLLECTION;
        $slug = $type->getSlug($value);

        /** @var Feature|null $feature */
        $feature = $this->featureService->getFeatureCollectionByFilter(
            new FeatureFilter(type: $type, slug: $slug)
        )->first();

        if (null !== $feature) {
            return $feature;
        }

        return $this->featureService->createFeature(
            new FeatureBlueprint(
                type: $type,
                value: $value,
                slug: $slug
            )
        );
    }
}
