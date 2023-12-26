<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\Collection;

use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class CollectionControllerDeleteTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/collections/collection/';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
        Sanctum::actingAs($this->admin);
    }

    public function testSuccessful()
    {
        /** @var \App\Modules\Collections\Models\Collection $collection */
        $collection = $this->createCollections(1)->first();

        $response = $this->delete(self::METHOD . $collection->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
        self::assertModelMissing($collection);
    }

    public function testFailure()
    {
        $response = $this->delete(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
