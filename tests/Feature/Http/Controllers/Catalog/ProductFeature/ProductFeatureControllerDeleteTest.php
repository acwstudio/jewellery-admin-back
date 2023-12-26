<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\ProductFeature;

use App\Modules\Catalog\Models\ProductFeature;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductFeatureControllerDeleteTest extends TestCase
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

        $response = $this->delete(self::METHOD . $productFeature->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }

    public function testFailure()
    {
        $response = $this->delete(self::METHOD . fake()->uuid());
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureForbidden()
    {
        Sanctum::actingAs($this->getUser());

        /** @var ProductFeature $productFeature */
        $productFeature = ProductFeature::factory()->create();

        $response = $this->delete(self::METHOD . $productFeature->getKey());
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
