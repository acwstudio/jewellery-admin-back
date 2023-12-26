<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Blog;

use App\Modules\Blog\Models\Category;
use Tests\TestCase;

class CategoryControllerGetListTest extends TestCase
{
    private const METHOD = '/api/v1/blog/category';

    public function testSuccessful()
    {
        Category::factory(5)->create();

        $response = $this->get(self::METHOD . '?pagination[page]=1&pagination[per_page]=3');
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertNotEmpty($content['items']);
        self::assertCount(3, $content['items']);
        self::assertArrayHasKey('pagination', $content);
    }

    public function testSuccessfulNextPage()
    {
        Category::factory(5)->create();

        $response = $this->get(self::METHOD . '?pagination[page]=2&pagination[per_page]=3');
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertNotEmpty($content['items']);
        self::assertCount(2, $content['items']);
        self::assertArrayHasKey('pagination', $content);
    }

    public function testSuccessfulAll()
    {
        Category::factory(5)->create();

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertNotEmpty($content['items']);
        self::assertCount(5, $content['items']);
        self::assertArrayHasKey('pagination', $content);
    }

    public function testSuccessfulSortById()
    {
        Category::factory(5)->create();

        $response = $this->get(self::METHOD . '?sort[sort_by]=id&sort[sort_order]=desc');
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);
        self::assertArrayHasKey('id', $content['items'][0]);
        $firstId = $content['items'][0]['id'];
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertEquals($firstId, $item['id']);
            $firstId--;
        }

        $response = $this->get(self::METHOD . '?sort[sort_by]=id&sort[sort_order]=asc');
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);
        self::assertArrayHasKey('id', $content['items'][0]);
        $firstId = $content['items'][0]['id'];
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertEquals($firstId, $item['id']);
            $firstId++;
        }
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD . '?pagination[page]=1&pagination[per_page]=3');
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertEmpty($content['items']);
        self::assertArrayHasKey('pagination', $content);
    }

    public function testFailureAll()
    {
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertEmpty($content['items']);
        self::assertArrayHasKey('pagination', $content);
    }
}
