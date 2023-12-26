<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Seo;

use App\Modules\Catalog\Models\Seo;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SeoControllerUpdateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/seo/';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        /** @var Seo $seo */
        $seo = Seo::factory()->create();

        $response = $this->put(self::METHOD . $seo->getKey(), [
            'category_id' => $seo->category_id,
            'filter' => ['in_stock' => true],
            'h1' => fake()->text(10),
        ]);

        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('parent_id', $content);
        self::assertArrayHasKey('category_id', $content);
        self::assertArrayHasKey('filter', $content);
        self::assertArrayHasKey('h1', $content);
        self::assertArrayHasKey('url', $content);
        self::assertArrayHasKey('meta_title', $content);
        self::assertArrayHasKey('meta_description', $content);
    }

    public function testSuccessfulByParent()
    {
        /** @var Seo $parent */
        $parent = Seo::factory()->create();

        /** @var Seo $seo */
        $seo = Seo::factory()->create();

        $response = $this->put(self::METHOD . $seo->getKey(), [
            'category_id' => $seo->category_id,
            'filter' => ['in_stock' => true],
            'h1' => fake()->text(10),
            'parent_id' => $parent->getKey()
        ]);

        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('parent_id', $content);
        self::assertEquals($parent->getKey(), $content['parent_id']);
    }

    public function testFailure()
    {
        $response = $this->put(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureForbidden()
    {
        Sanctum::actingAs($this->getUser());

        /** @var Seo $seo */
        $seo = Seo::factory()->create();

        $response = $this->put(self::METHOD . $seo->getKey(), [
            'category_id' => $seo->category_id,
            'filter' => ['in_stock' => true],
            'h1' => fake()->text(10),
        ]);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureByParent()
    {
        /** @var Seo $seo */
        $seo = Seo::factory()->create();

        $response = $this->put(self::METHOD . $seo->getKey(), [
            'category_id' => $seo->category_id,
            'filter' => ['in_stock' => true],
            'h1' => fake()->text(10),
            'parent_id' => $seo->getKey()
        ]);

        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureByUrl()
    {
        /** @var Seo $seo */
        $seo = Seo::factory()->create();

        $response = $this->put(self::METHOD . $seo->getKey(), [
            'category_id' => $seo->category_id,
            'filter' => ['in_stock' => true],
            'h1' => fake()->text(10),
            'url' => $seo->url,
        ]);

        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
