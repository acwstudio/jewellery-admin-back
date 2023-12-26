<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Feature;

use App\Modules\Catalog\Models\Feature;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FeatureControllerDeleteTest extends TestCase
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

        $response = $this->delete(self::METHOD . $feature->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
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

        /** @var Feature $feature */
        $feature = Feature::factory()->create();

        $response = $this->delete(self::METHOD . $feature->getKey());
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
