<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Blog;

use App\Modules\Blog\Models\Category;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\Exceptions\Blog\CategoryNotFoundException;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryControllerUpdateTest extends TestCase
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
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->put(self::METHOD, [
            'id' => $category->id,
            'slug' => 'category_slug',
            'name' => 'Категория 1'
        ]);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertEquals($category->id, $content['id']);
        self::assertArrayHasKey('slug', $content);
        self::assertEquals('category-slug', $content['slug']);
        self::assertArrayHasKey('name', $content);
        self::assertEquals('Категория 1', $content['name']);
        self::assertArrayHasKey('position', $content);
        self::assertEquals(10, $content['position']);
    }

    public function testSuccessfulPosition()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->put(self::METHOD, [
            'id' => $category->id,
            'slug' => 'category_slug',
            'name' => 'Категория 1',
            'position' => 15
        ]);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertEquals($category->id, $content['id']);
        self::assertArrayHasKey('slug', $content);
        self::assertEquals('category-slug', $content['slug']);
        self::assertArrayHasKey('name', $content);
        self::assertEquals('Категория 1', $content['name']);
        self::assertArrayHasKey('position', $content);
        self::assertEquals(15, $content['position']);
    }

    public function testFailure()
    {
        $response = $this->put(self::METHOD, [
            'id' => 10,
            'slug' => 'category_slug',
            'name' => 'Категория 1',
            'position' => 1
        ]);
        $response->assertServerError();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
        self::assertEquals((new CategoryNotFoundException())->getCode(), $content['error']['code']);
    }

    public function testFailureDuplicateSlug()
    {
        /** @var Category $categoryOne */
        $categoryOne = Category::factory()->create();

        /** @var Category $categoryTwo */
        $categoryTwo = Category::factory()->create();

        $response = $this->put(self::METHOD, [
            'id' => $categoryOne->id,
            'slug' => $categoryTwo->slug,
            'name' => 'Категория 1',
            'position' => 1
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

        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->put(self::METHOD, [
            'id' => $category->id,
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
