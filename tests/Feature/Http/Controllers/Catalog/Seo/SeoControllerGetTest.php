<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Seo;

use App\Modules\Catalog\Models\Seo;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SeoControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/seo/';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        $seo = Seo::factory()->create();

        $response = $this->get(self::METHOD . $seo->getKey());
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

    public function testFailure()
    {
        $response = $this->get(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureForbidden()
    {
        Sanctum::actingAs($this->getUser());

        $seo = Seo::factory()->create();

        $response = $this->get(self::METHOD . $seo->getKey());
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
