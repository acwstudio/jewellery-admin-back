<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\PreviewImage;

use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PreviewImageControllerUploadTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/preview_image';
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
        $files['image'] = UploadedFile::fake()->image('image.jpg');

        $response = $this->sendByFiles('POST', self::METHOD, [], $files);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('image_url_sm', $content);
        self::assertArrayHasKey('image_url_md', $content);
        self::assertArrayHasKey('image_url_lg', $content);
    }

    public function testSuccessfulSmallImage()
    {
        $files['image'] = UploadedFile::fake()->image('image.jpg', 1, 1);

        $response = $this->sendByFiles('POST', self::METHOD, [], $files);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('image_url_lg', $content);
        self::assertArrayHasKey('image_url_sm', $content);
        self::assertArrayHasKey('image_url_md', $content);
    }

    public function testSuccessfulBigImage()
    {
        $files['image'] = UploadedFile::fake()->image('image.jpg', 1500, 1300);

        $response = $this->sendByFiles('POST', self::METHOD, [], $files);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('image_url_lg', $content);
        self::assertArrayHasKey('image_url_sm', $content);
        self::assertArrayHasKey('image_url_md', $content);
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

    public function testFailureAccessDenied()
    {
        Sanctum::actingAs($this->getUser());

        $files['image'] = UploadedFile::fake()->image('image.jpg');

        $response = $this->sendByFiles('POST', self::METHOD, [], $files);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
    }
}
