<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductImageUrl;
use App\Modules\Catalog\Services\ProductImageUrlService;
use App\Modules\Catalog\Support\Blueprints\ProductImageUrlBlueprint;
use Tests\TestCase;

class ProductImageUrlServiceTest extends TestCase
{
    private ProductImageUrlService $productImageUrlService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productImageUrlService = app(ProductImageUrlService::class);
    }

    public function testSuccessfulGet()
    {
        /** @var ProductImageUrl $productImageUrl */
        $productImageUrl = ProductImageUrl::factory()->create();

        $results = $this->productImageUrlService->getProductImageUrl($productImageUrl->getKey());

        self::assertInstanceOf(ProductImageUrl::class, $results);
    }

    public function testSuccessfulCreate()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $data = new ProductImageUrlBlueprint(
            fake()->imageUrl(),
            true,
        );

        $results = $this->productImageUrlService->createProductImageUrl($data, $product);

        self::assertInstanceOf(ProductImageUrl::class, $results);
    }

    public function testSuccessfulUpdate()
    {
        /** @var ProductImageUrl $productImageUrl */
        $productImageUrl = ProductImageUrl::factory()->create();

        $data = new ProductImageUrlBlueprint(
            'image.png',
            false,
        );

        $results = $this->productImageUrlService->updateProductImageUrl($productImageUrl, $data);

        self::assertInstanceOf(ProductImageUrl::class, $results);
        self::assertEquals('image.png', $results->path);
        self::assertFalse($results->is_main);
    }

    public function testSuccessfulDelete()
    {
        /** @var ProductImageUrl $productImageUrl */
        $productImageUrl = ProductImageUrl::factory()->create();

        $this->productImageUrlService->deleteProductImageUrl($productImageUrl->getKey());

        self::assertModelMissing($productImageUrl);
    }
}
