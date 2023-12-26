<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\Stone;

use App\Modules\Collections\Models\Stone;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class StoneControllerDeleteTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/collections/stone/';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
        Sanctum::actingAs($this->admin);
    }

    public function testSuccessful()
    {
        /** @var Stone $stone */
        $stone = Stone::factory()->create();

        $response = $this->delete(self::METHOD . $stone->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
        self::assertModelMissing($stone);
    }

    public function testFailure()
    {
        $response = $this->delete(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
