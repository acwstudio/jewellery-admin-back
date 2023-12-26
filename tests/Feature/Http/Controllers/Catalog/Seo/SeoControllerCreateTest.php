<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Seo;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Seo;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SeoControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/seo';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        $category = Category::factory()->create();

        $data = [
            'category_id' => $category->getKey(),
            'filter' => [
                'feature' => [
                    'metal' => '1',
                    'metal_color' => '2'
                ]
            ],
            'h1' => fake()->text(10),
            'url' => fake()->url()
        ];

        $response = $this->post(self::METHOD, $data);
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
        $parent = Seo::factory()->create();
        $category = Category::factory()->create();

        $data = [
            'category_id' => $category->getKey(),
            'filter' => [
                'feature' => [
                    'metal' => '1',
                    'metal_color' => '2'
                ]
            ],
            'h1' => fake()->text(10),
            'parent_id' => $parent->getKey(),
            'url' => fake()->url()
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('parent_id', $content);
        self::assertEquals($parent->getKey(), $content['parent_id']);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureDuplicateUrl()
    {
        /** @var Seo $seo */
        $seo = Seo::factory()->create();
        $category = Category::factory()->create();

        $data = [
            'category_id' => $category->getKey(),
            'filter' => [],
            'h1' => fake()->text(10),
            'url' => $seo->url
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureForbidden()
    {
        Sanctum::actingAs($this->getUser());

        $category = Category::factory()->create();

        $data = [
            'category_id' => $category->getKey(),
            'filter' => [
                'feature' => [
                    'metal' => '1',
                    'metal_color' => '2'
                ]
            ],
            'h1' => fake()->text(10),
            'url' => fake()->url()
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
