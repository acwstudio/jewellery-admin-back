<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Blog;

use App\Modules\Blog\Models\Category;
use App\Modules\Blog\Models\Post;
use App\Modules\Storage\Models\File;
use App\Modules\Storage\Models\Media;
use App\Packages\Enums\PostStatusEnum;
use App\Packages\Exceptions\Blog\CategoryNotFoundException;
use Tests\TestCase;

class PostControllerGetListTest extends TestCase
{
    private const METHOD = '/api/v1/blog/post';

    public function testSuccessful()
    {
        Post::factory(5)->create(['status' => PostStatusEnum::PUBLISHED]);

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
        Post::factory(5)->create(['status' => PostStatusEnum::PUBLISHED]);

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
        Post::factory(5)->create(['status' => PostStatusEnum::PUBLISHED]);

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
        Post::factory(5)->create(['status' => PostStatusEnum::PUBLISHED]);

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
        $response->assertOk();
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

    public function testSuccessfulByCategory()
    {
        /** @var Category $category */
        $category = Category::factory()->create();
        Post::factory(3)->create(['status' => PostStatusEnum::PUBLISHED, 'category_id' => $category->id]);

        Post::factory(3)->create(['status' => PostStatusEnum::PUBLISHED]);

        $response = $this->get(
            self::METHOD . "?category={$category->slug}&pagination[page]=1&pagination[per_page]=10"
        );
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertNotEmpty($content['items']);
        self::assertCount(3, $content['items']);
        self::assertArrayHasKey('pagination', $content);
    }

    public function testSuccessfulByPreview()
    {
        $file = $this->getFile();

        Post::factory(3)->create(['status' => PostStatusEnum::PUBLISHED, 'preview_id' => $file->id]);

        $response = $this->get(
            self::METHOD . "?pagination[page]=1&pagination[per_page]=10"
        );
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertNotEmpty($content['items']);
        self::assertCount(3, $content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('preview', $item);
            self::assertNotEmpty($item['preview']);
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

    public function testFailureByCategory()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        Post::factory(3)->create(['status' => PostStatusEnum::PUBLISHED]);

        $response = $this->get(
            self::METHOD . "?category={$category->slug}&pagination[page]=1&pagination[per_page]=10"
        );
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertEmpty($content['items']);
        self::assertArrayHasKey('pagination', $content);
    }

    public function testFailureByCategoryNotFound()
    {
        Post::factory(3)->create(['status' => PostStatusEnum::PUBLISHED]);

        $response = $this->get(
            self::METHOD . "?category=not-found&pagination[page]=1&pagination[per_page]=10"
        );
        $response->assertServerError();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
        self::assertEquals((new CategoryNotFoundException())->getCode(), $content['error']['code']);
    }

    public function testFailureSortByUnknownColumn()
    {
        Post::factory(5)->create(['status' => PostStatusEnum::PUBLISHED]);

        $response = $this->get(self::METHOD . '?sort[sort_by]=unknown&sort[sort_order]=desc');
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
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
