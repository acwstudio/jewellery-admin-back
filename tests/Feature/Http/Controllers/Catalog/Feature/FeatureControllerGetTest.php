<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Feature;

use App\Modules\Catalog\Models\Feature;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeatureControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/feature/';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        $feature = Feature::factory()->create();

        $response = $this->get(self::METHOD . $feature->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('type', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('value', $content);
        self::assertArrayHasKey('slug', $content);
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

        $response = $this->get(self::METHOD . 100500);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
