<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Seo;

use App\Modules\Catalog\Models\Seo;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SeoControllerGetListTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/seo';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        Seo::factory(5)->create();

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertIsArray($content);
        self::assertArrayNotHasKey('error', $content);
        self::assertCount(5, $content);
    }

    public function testSuccessfulEmpty()
    {
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertIsArray($content);
        self::assertArrayNotHasKey('error', $content);
        self::assertEmpty($content);
    }

    public function testFailureForbidden()
    {
        Sanctum::actingAs($this->getUser());

        Seo::factory(5)->create();

        $response = $this->get(self::METHOD);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
