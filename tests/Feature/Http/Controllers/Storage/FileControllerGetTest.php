<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Storage;

use App\Modules\Storage\Models\File;
use App\Modules\Storage\Models\Media;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\Exceptions\Storage\FileNotFoundException;
use Tests\TestCase;

class FileControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/storage/file/';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
    }

    public function testSuccessful()
    {
        $file = $this->getFile();

        $response = $this->actingAs($this->admin)->get(self::METHOD . $file->id);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('file_name', $content);
        self::assertArrayHasKey('url', $content);
        self::assertArrayHasKey('type', $content);
    }

    public function testFailure()
    {
        $response = $this->actingAs($this->admin)->get(self::METHOD . rand(10, 100));
        $response->assertServerError();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
        self::assertEquals((new FileNotFoundException())->getCode(), $content['error']['code']);
    }

    public function testFailureAuth()
    {
        $response = $this->get(self::METHOD . rand(10, 100));
        $response->assertServerError();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
    }

    public function testFailureRoleUser()
    {
        $user = $this->getUser(RoleEnum::USER);

        $response = $this->actingAs($user)->get(self::METHOD . rand(10, 100));
        $response->assertForbidden();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
    }

    private function getFile(): File
    {
        /** @var File $file */
        $file = File::factory()->create();
        Media::factory()->create([
            'model_type' => File::class,
            'model_id' => $file->getKey()
        ]);

        return $file;
    }
}
