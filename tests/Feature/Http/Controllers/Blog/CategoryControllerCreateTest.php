<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Blog;

use App\Modules\Blog\Models\Category;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/blog/category';
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
            'slug' => 'category_slug',
            'name' => 'Категория 1'
        ]);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('slug', $content);
        self::assertEquals(Str::slug('category_slug'), $content['slug']);
        self::assertArrayHasKey('name', $content);
        self::assertEquals('Категория 1', $content['name']);
        self::assertArrayHasKey('position', $content);
        self::assertEquals(10, $content['position']);
    }

    public function testSuccessfulTranslit()
    {
        $response = $this->post(self::METHOD, [
            'slug' => ' Категория One ',
            'name' => 'Категория 1',
        ]);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('slug', $content);
        self::assertNotEquals('Категория One', $content['slug']);
        self::assertEquals(Str::slug('Категория One'), $content['slug']);
    }

    public function testSuccessfulPosition()
    {
        $response = $this->post(self::METHOD, [
            'slug' => 'category_slug',
            'name' => 'Категория 1',
            'position' => 15
        ]);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('slug', $content);
        self::assertEquals(Str::slug('category_slug'), $content['slug']);
        self::assertArrayHasKey('name', $content);
        self::assertEquals('Категория 1', $content['name']);
        self::assertArrayHasKey('position', $content);
        self::assertEquals(15, $content['position']);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD);
        $response->assertServerError();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
    }

    public function testFailureDuplicateSlug()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->post(self::METHOD, [
            'slug' => $category->slug,
            'name' => 'Категория 1'
        ]);
        $response->assertServerError();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
    }

    public function testFailureAccessDenied()
    {
        Sanctum::actingAs($this->getUser());

        $response = $this->post(self::METHOD, [
            'slug' => 'category_slug',
            'name' => 'Категория 1'
        ]);
        $response->assertForbidden();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
    }
}
