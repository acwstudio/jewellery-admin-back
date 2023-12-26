<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\File;

use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FileControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/collections/file';
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
        $files['file'] = UploadedFile::fake()->image('image.jpg');

        $response = $this->sendByFiles('POST', self::METHOD, [], $files);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('url', $content);
        self::assertArrayHasKey('mime_type', $content);
    }

    public function testFailure()
    {
        $response = $this->sendByFiles('POST', self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
    }
}
