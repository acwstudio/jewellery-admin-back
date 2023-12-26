<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Feature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeatureControllerGetListTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/feature';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        Feature::factory(5)->create();

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);
    }

    public function testSuccessfulByPagination()
    {
        Feature::factory(5)->create();

        $query = [
            'pagination' => [
                'page' => 1,
                'per_page' => 3
            ]
        ];

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }

    public function testSuccessfulByFilterType()
    {
        Feature::factory(2)->create(['type' => FeatureTypeEnum::INSERT]);
        Feature::factory(3)->create(['type' => FeatureTypeEnum::METAL]);

        $query = [
            'filter' => [
                'type' => FeatureTypeEnum::METAL->value
            ]
        ];

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('type', $item);
            self::assertEquals(FeatureTypeEnum::METAL->value, $item['type']);
        }
    }

    public function testSuccessfulByFilterValue()
    {
        Feature::factory(3)->create();
        Feature::factory()->create(['type' => FeatureTypeEnum::METAL_COLOR, 'value' => 'Белый']);
        Feature::factory()->create(['type' => FeatureTypeEnum::INSERT_COLOR, 'value' => 'Белый']);

        $query = [
            'filter' => [
                'value' => 'Бел'
            ]
        ];

        $response = $this->get(self::METHOD . '?' . http_build_query($query));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('value', $item);
            self::assertEquals('Белый', $item['value']);
        }
    }

    public function testSuccessfulEmpty()
    {
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertEmpty($content['items']);
    }

    public function testFailureForbidden()
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->get(self::METHOD);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
