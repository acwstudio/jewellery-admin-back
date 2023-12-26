<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Feature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeatureControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/feature';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        $response = $this->post(self::METHOD, [
            'type' => FeatureTypeEnum::METAL->value,
            'value' => 'Серебро'
        ]);

        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('type', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('value', $content);
        self::assertArrayHasKey('slug', $content);
        $slug = Str::slug('Серебро', '_');
        self::assertEquals(FeatureTypeEnum::METAL->getSlug($slug), $content['slug']);
    }

    public function testSuccessfulByInsert()
    {
        $response = $this->post(self::METHOD, [
            'type' => FeatureTypeEnum::INSERT->value,
            'value' => 'Бриллиант'
        ]);

        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('type', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('value', $content);
        self::assertArrayHasKey('slug', $content);
        $slug = Str::slug('Бриллиант', '_');
        self::assertEquals(FeatureTypeEnum::INSERT->getSlug($slug), $content['slug']);
    }

    public function testSuccessfulByMetalColor()
    {
        $response = $this->post(self::METHOD, [
            'type' => FeatureTypeEnum::METAL_COLOR->value,
            'value' => 'Белое'
        ]);

        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('type', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('value', $content);
        self::assertArrayHasKey('slug', $content);
        $slug = Str::slug('Белое', '_');
        self::assertEquals(FeatureTypeEnum::METAL_COLOR->getSlug($slug), $content['slug']);
    }

    public function testSuccessfulByProbe()
    {
        $response = $this->post(self::METHOD, [
            'type' => FeatureTypeEnum::PROBE->value,
            'value' => '525'
        ]);

        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('type', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('value', $content);
        self::assertArrayHasKey('slug', $content);
        self::assertEquals(FeatureTypeEnum::PROBE->getSlug('525'), $content['slug']);
    }

    public function testSuccessfulCustomSlug()
    {
        $response = $this->post(self::METHOD, [
            'type' => FeatureTypeEnum::PROBE->value,
            'value' => '525',
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
        $response = $this->post(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureDuplicate()
    {
        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $response = $this->post(self::METHOD, [
            'type' => $feature->type->value,
            'value' => $feature->value
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureForbidden()
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->post(self::METHOD, [
            'type' => FeatureTypeEnum::METAL->value,
            'value' => fake()->text(10)
        ]);

        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureUnique()
    {
        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $response = $this->post(self::METHOD, [
            'type' => FeatureTypeEnum::METAL_COLOR->value,
            'value' => 'Белое',
            'slug' => $feature->slug
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
