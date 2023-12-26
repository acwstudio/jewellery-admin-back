<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Seo;

use App\Modules\Catalog\Models\Seo;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SeoControllerDeleteTest extends TestCase
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

        $response = $this->delete(self::METHOD . $seo->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
        self::assertModelMissing($seo);
    }

    public function testSuccessfulByParent()
    {
        /** @var Seo $parent */
        $parent = Seo::factory()->create();

        /** @var Seo $seo */
        $seo = Seo::factory()->create(['parent_id' => $parent->getKey()]);
        self::assertNotEmpty($seo->parent_id);

        $response = $this->delete(self::METHOD . $parent->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
        self::assertModelMissing($parent);
        self::assertModelMissing($seo);
    }

    public function testSuccessfulIncludeParent()
    {
        /** @var Seo $parent */
        $parent = Seo::factory()->create();

        /** @var Seo $seo */
        $seo = Seo::factory()->create(['parent_id' => $parent->getKey()]);
        self::assertNotEmpty($seo->parent_id);

        $response = $this->delete(self::METHOD . $seo->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
        self::assertModelMissing($seo);
        self::assertModelExists($parent);
    }

    public function testFailure()
    {
        $response = $this->delete(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureForbidden()
    {
        Sanctum::actingAs($this->getUser());

        /** @var Seo $seo */
        $seo = Seo::factory()->create();

        $response = $this->delete(self::METHOD . $seo->getKey());
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
