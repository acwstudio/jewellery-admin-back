<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Storage;

use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileControllerUploadTest extends TestCase
{
    private const METHOD = '/api/v1/storage/file/upload';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->admin = $this->getUser(RoleEnum::ADMIN);
    }

    public function testSuccessful()
    {
        $files['files'] = [
            UploadedFile::fake()->image('image.jpg'),
            UploadedFile::fake()->create('video.mp4')
        ];

        $response = $this->actingAs($this->admin)->sendByFiles('POST', self::METHOD, [], $files);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('files', $content);
        self::assertIsArray($content['files']);
        self::assertCount(2, $content['files']);
        foreach ($content['files'] as $file) {
            self::assertArrayHasKey('id', $file);
            self::assertArrayHasKey('file_name', $file);
            self::assertArrayHasKey('url', $file);
            self::assertArrayHasKey('type', $file);
        }
    }

    public function testFailure()
    {
        $files['files'] = [];

        $response = $this->actingAs($this->admin)->sendByFiles('POST', self::METHOD, [], $files);
        $response->assertServerError();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
    }

    public function testFailureAccessDenied()
    {
        $files['files'] = [
            UploadedFile::fake()->image('image.jpg'),
            UploadedFile::fake()->create('video.mp4')
        ];

        $response = $this->actingAs($this->getUser(RoleEnum::USER))
            ->sendByFiles('POST', self::METHOD, [], $files);
        $response->assertForbidden();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
    }
}
