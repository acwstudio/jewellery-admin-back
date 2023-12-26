<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Storage;

use App\Modules\Storage\Models\File;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\Exceptions\Storage\FileNotFoundException;
use Tests\TestCase;

class FileControllerDeleteTest extends TestCase
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
        /** @var File $file */
        $file = File::factory()->create();

        $response = $this->actingAs($this->admin)->delete(self::METHOD . $file->id);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('success', $content);
        self::assertTrue($content['success']);
    }

    public function testFailure()
    {
        $response = $this->actingAs($this->admin)->delete(self::METHOD . rand(10, 100));
        $response->assertServerError();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
        self::assertEquals((new FileNotFoundException())->getCode(), $content['error']['code']);
    }

    public function testFailureAccessDenied()
    {
        $response = $this->actingAs($this->getUser(RoleEnum::USER))->delete(self::METHOD . rand(10, 100));
        $response->assertForbidden();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
    }
}
