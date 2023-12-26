<?php

declare(strict_types=1);

namespace Http\Controllers\Blog;

use App\Modules\Blog\Models\Category;
use App\Packages\Exceptions\Blog\CategoryNotFoundException;
use Tests\TestCase;

class CategoryControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/blog/category/';

    public function testSuccessful()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->get(self::METHOD . $category->slug);
        $response->assertStatus(200);

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertEquals($category->id, $content['id']);
        self::assertArrayHasKey('slug', $content);
        self::assertEquals($category->slug, $content['slug']);
        self::assertArrayHasKey('name', $content);
        self::assertEquals($category->name, $content['name']);
        self::assertArrayHasKey('position', $content);
        self::assertEquals($category->position, $content['position']);
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD . 'not_found');
        $response->assertStatus(500);

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
        self::assertEquals((new CategoryNotFoundException())->getCode(), $content['error']['code']);
    }
}
