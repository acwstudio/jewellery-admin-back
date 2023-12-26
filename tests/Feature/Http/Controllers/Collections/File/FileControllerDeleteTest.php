<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\File;

use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class FileControllerDeleteTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/collections/file/';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
        Sanctum::actingAs($this->admin);
    }

    public function testSuccessful()
    {
        /** @var \App\Modules\Collections\Models\File $file */
        $file = $this->createFiles(1)->first();

        $response = $this->delete(self::METHOD . $file->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
        self::assertModelMissing($file);
    }

    public function testFailure()
    {
        $response = $this->delete(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
