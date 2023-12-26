<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\ProductFeature;

use App\Modules\Catalog\Models\ProductFeature;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductFeatureControllerUpdateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/product_feature/';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        /** @var ProductFeature $productFeature */
        $productFeature = ProductFeature::factory()->create();

        $response = $this->put(self::METHOD . $productFeature->getKey(), [
            'value' => '10'
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('uuid', $content);
        self::assertArrayHasKey('feature', $content);
        self::assertArrayHasKey('children', $content);
        self::assertArrayHasKey('value', $content);
        self::assertNotEmpty($content['value']);
    }

    public function testSuccessfulEmptyCount()
    {
        /** @var ProductFeature $productFeature */
        $productFeature = ProductFeature::factory()->create();

        $response = $this->put(self::METHOD . $productFeature->getKey(), [
            'value' => null
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('uuid', $content);
        self::assertArrayHasKey('feature', $content);
        self::assertArrayHasKey('children', $content);
        self::assertArrayHasKey('value', $content);
        self::assertEmpty($content['value']);
    }

    public function testFailure()
    {
        $response = $this->put(self::METHOD . fake()->uuid());
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureForbidden()
    {
        Sanctum::actingAs($this->getUser());

        /** @var ProductFeature $productFeature */
        $productFeature = ProductFeature::factory()->create();

        $response = $this->put(self::METHOD . $productFeature->getKey(), [
            'value' => '1'
        ]);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
