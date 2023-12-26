<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\ProductFeature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductFeatureControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/product_feature';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'feature_id' => $feature->getKey(),
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('uuid', $content);
        self::assertArrayHasKey('feature', $content);
        $this->assertFeatureData($content['feature']);
        self::assertArrayHasKey('children', $content);
        self::assertEmpty($content['children']);
        self::assertArrayHasKey('is_main', $content);
        self::assertFalse($content['is_main']);
        self::assertArrayHasKey('value', $content);
        self::assertEmpty($content['value']);
    }

    public function testSuccessfulFull()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        /** @var Feature $feature */
        $feature = Feature::factory()->create(['type' => FeatureTypeEnum::INSERT]);

        /** @var ProductFeature $parentProductFeature */
        $parentProductFeature = ProductFeature::factory()->create();

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'feature_id' => $feature->getKey(),
            'parent_product_feature_uuid' => $parentProductFeature->getKey(),
            'value' => '10'
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('uuid', $content);
        self::assertArrayHasKey('feature', $content);
        $this->assertFeatureData($content['feature']);
        self::assertArrayHasKey('children', $content);
        self::assertEmpty($content['children']);
        self::assertArrayHasKey('is_main', $content);
        self::assertFalse($content['is_main']);
        self::assertArrayHasKey('value', $content);
        self::assertEquals('10', $content['value']);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureParentUuid()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        /** @var Feature $feature */
        $feature = Feature::factory()->create(['type' => FeatureTypeEnum::INSERT]);

        /** @var ProductFeature $parentProductFeature */
        $parentProductFeature = ProductFeature::factory()->create([
            'parent_uuid' => ProductFeature::factory()->create()
        ]);

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'feature_id' => $feature->getKey(),
            'parent_product_feature_uuid' => $parentProductFeature->getKey()
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureEmptyProductId()
    {
        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $response = $this->post(self::METHOD, [
            'product_id' => 100500,
            'feature_id' => $feature->getKey()
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureEmptyFeatureId()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'feature_id' => 100500
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureValue()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'feature_id' => $feature->getKey(),
            'value' => 10
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureForbidden()
    {
        Sanctum::actingAs($this->getUser());

        /** @var Product $product */
        $product = Product::factory()->create();

        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $response = $this->post(self::METHOD, [
            'product_id' => $product->getKey(),
            'feature_id' => $feature->getKey()
        ]);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function assertFeatureData(array $featureData): void
    {
        self::assertArrayHasKey('id', $featureData);
        self::assertNotEmpty($featureData['id']);
        self::assertArrayHasKey('type', $featureData);
        self::assertNotEmpty($featureData['type']);
        self::assertArrayHasKey('name', $featureData);
        self::assertNotEmpty($featureData['name']);
        self::assertArrayHasKey('value', $featureData);
        self::assertNotEmpty($featureData['value']);
    }
}
