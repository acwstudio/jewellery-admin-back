<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Feature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeatureControllerUpdateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/feature/';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $response = $this->put(self::METHOD . $feature->getKey(), [
            'type' => FeatureTypeEnum::METAL->value,
            'value' => fake()->text(10)
        ]);

        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('type', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('value', $content);
        self::assertArrayHasKey('slug', $content);
    }

    public function testSuccessfulBySlug()
    {
        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $response = $this->put(self::METHOD . $feature->getKey(), [
            'type' => FeatureTypeEnum::METAL->value,
            'value' => fake()->text(10),
            'slug' => 'custom-slug'
        ]);

        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('type', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('value', $content);
        self::assertArrayHasKey('slug', $content);
        self::assertEquals('custom_slug', $content['slug']);
    }

    public function testFailure()
    {
        $response = $this->put(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureDuplicate()
    {
        /** @var Feature $featureDuplicate */
        $featureDuplicate = Feature::factory()->create();

        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $response = $this->put(self::METHOD . $feature->getKey(), [
            'type' => $featureDuplicate->type->value,
            'value' => $featureDuplicate->value
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureForbidden()
    {
        Sanctum::actingAs($this->getUser());

        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $response = $this->put(self::METHOD . $feature->getKey(), [
            'type' => FeatureTypeEnum::METAL->value,
            'value' => fake()->text(10)
        ]);

        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
