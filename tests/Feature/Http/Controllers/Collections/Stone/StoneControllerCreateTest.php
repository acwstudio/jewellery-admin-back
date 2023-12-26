<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\Stone;

use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class StoneControllerCreateTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/collections/stone';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
        Sanctum::actingAs($this->admin);
    }

    public function testSuccessful()
    {
        $response = $this->post(self::METHOD, [
            'name' => fake()->text(50)
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('name', $content);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
