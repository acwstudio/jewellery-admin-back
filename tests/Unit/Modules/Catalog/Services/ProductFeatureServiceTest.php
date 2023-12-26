<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Services\ProductFeatureService;
use App\Modules\Catalog\Support\Blueprints\ProductFeatureBlueprint;
use Tests\TestCase;

class ProductFeatureServiceTest extends TestCase
{
    private ProductFeatureService $productFeatureService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productFeatureService = app(ProductFeatureService::class);
    }

    public function testSuccessfulGet()
    {
        /** @var ProductFeature $productFeature */
        $productFeature = ProductFeature::factory()->create();

        $results = $this->productFeatureService->getProductFeature($productFeature->getKey());

        self::assertInstanceOf(ProductFeature::class, $results);
    }

    public function testSuccessfulCreate()
    {
        /** @var ProductFeature $parentProductFeature */
        $parentProductFeature = ProductFeature::factory()->create();
        /** @var Product $product */
        $product = Product::factory()->create();
        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $data = new ProductFeatureBlueprint();

        $results = $this->productFeatureService->createProductFeature(
            $data,
            $product,
            $feature,
            $parentProductFeature
        );

        self::assertInstanceOf(ProductFeature::class, $results);
    }

    public function testSuccessfulUpdate()
    {
        /** @var ProductFeature $productFeature */
        $productFeature = ProductFeature::factory()->create();

        $data = new ProductFeatureBlueprint('10');

        $results = $this->productFeatureService->updateProductFeature(
            $productFeature,
            $data
        );

        self::assertInstanceOf(ProductFeature::class, $results);
        self::assertEquals('10', $results->value);
    }

    public function testSuccessfulDelete()
    {
        /** @var ProductFeature $productFeature */
        $productFeature = ProductFeature::factory()->create();

        $this->productFeatureService->deleteProductFeature($productFeature->getKey());

        self::assertModelMissing($productFeature);
    }
}
