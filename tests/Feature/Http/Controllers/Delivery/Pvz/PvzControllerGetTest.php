<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Delivery\Pvz;

use App\Modules\Delivery\Models\Pvz;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PvzControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/delivery/pvz';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        Pvz::factory(5)->create(['city' => config('delivery.default_city')]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertCount(5, $content);
    }

    public function testSuccessfulEmpty()
    {
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }
}
