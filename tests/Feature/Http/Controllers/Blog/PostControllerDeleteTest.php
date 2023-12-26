<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Blog;

use App\Modules\Blog\Models\Post;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\Exceptions\Blog\PostNotFoundException;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostControllerDeleteTest extends TestCase
{
    private const METHOD = '/api/v1/blog/post/';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
        Sanctum::actingAs($this->admin);
    }

    public function testSuccessful()
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        $response = $this->delete(self::METHOD . $post->id);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('success', $content);
        self::assertTrue($content['success']);
    }

    public function testFailure()
    {
        $response = $this->delete(self::METHOD . 10);
        $response->assertServerError();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
        self::assertEquals((new PostNotFoundException())->getCode(), $content['error']['code']);
    }

    public function testFailureAccessDenied()
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->delete(self::METHOD . 10);
        $response->assertForbidden();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
    }
}
