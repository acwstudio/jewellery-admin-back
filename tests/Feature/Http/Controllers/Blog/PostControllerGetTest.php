<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Blog;

use App\Modules\Blog\Models\Post;
use App\Modules\Storage\Models\File;
use App\Modules\Storage\Models\Media;
use App\Packages\Enums\PostStatusEnum;
use App\Packages\Exceptions\Blog\PostNotFoundException;
use Tests\TestCase;

class PostControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/blog/post/';

    public function testSuccessful()
    {
        /** @var Post $post */
        $post = Post::factory()->create(['status' => PostStatusEnum::PUBLISHED]);

        $response = $this->get(self::METHOD . $post->slug);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertEquals($post->id, $content['id']);
        self::assertArrayHasKey('slug', $content);
        self::assertEquals($post->slug, $content['slug']);
        self::assertArrayHasKey('title', $content);
        self::assertEquals($post->title, $content['title']);
        self::assertArrayHasKey('status', $content);
        self::assertEquals($post->status->value, $content['status']);

        self::assertArrayHasKey('image', $content);
        self::assertEmpty($content['image']);
        self::assertArrayHasKey('preview', $content);
        self::assertEmpty($content['preview']);

        self::assertArrayHasKey('published_at', $content);
        self::assertEquals($post->published_at->format('Y-m-d\TH:i:sP'), $content['published_at']);

        /** @var \App\Modules\Blog\Models\Category $category */
        $category = $post->category()->get()->first();
        self::assertArrayHasKey('category', $content);
        self::assertIsArray($content['category']);
        self::assertArrayHasKey('id', $content['category']);
        self::assertEquals($category->id, $content['category']['id']);
        self::assertArrayHasKey('slug', $content['category']);
        self::assertEquals($category->slug, $content['category']['slug']);
        self::assertArrayHasKey('name', $content['category']);
        self::assertEquals($category->name, $content['category']['name']);
    }

    public function testSuccessfulRelatedPosts()
    {
        $relatedPosts = Post::factory(2)->create(['status' => PostStatusEnum::PUBLISHED]);

        /** @var Post $post */
        $post = Post::factory()->create(['status' => PostStatusEnum::PUBLISHED]);
        $post->relatedPosts()->attach($relatedPosts);

        $response = $this->get(self::METHOD . $post->slug);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertEquals($post->id, $content['id']);
        self::assertArrayHasKey('slug', $content);
        self::assertEquals($post->slug, $content['slug']);
        self::assertArrayHasKey('title', $content);
        self::assertEquals($post->title, $content['title']);
        self::assertArrayHasKey('status', $content);
        self::assertEquals($post->status->value, $content['status']);
        self::assertArrayHasKey('published_at', $content);
        self::assertEquals($post->published_at->format('Y-m-d\TH:i:sP'), $content['published_at']);

        self::assertArrayHasKey('related_posts', $content);
        self::assertIsArray($content['related_posts']);
        foreach ($content['related_posts'] as $relatedPost) {
            self::assertArrayHasKey('id', $relatedPost);
            self::assertArrayHasKey('category', $relatedPost);
            self::assertArrayHasKey('slug', $relatedPost);
            self::assertArrayHasKey('title', $relatedPost);
            self::assertArrayHasKey('published_at', $relatedPost);
        }
    }

    public function testSuccessfulImageAndPreview()
    {
        $file = $this->getFile();

        /** @var Post $post */
        $post = Post::factory()->create([
            'status' => PostStatusEnum::PUBLISHED,
            'preview_id' => $file->id,
            'image_id' => $file->id
        ]);

        $response = $this->get(self::METHOD . $post->slug);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertEquals($post->id, $content['id']);
        self::assertArrayHasKey('slug', $content);
        self::assertEquals($post->slug, $content['slug']);
        self::assertArrayHasKey('title', $content);
        self::assertEquals($post->title, $content['title']);
        self::assertArrayHasKey('status', $content);
        self::assertEquals($post->status->value, $content['status']);

        self::assertArrayHasKey('image', $content);
        self::assertNotEmpty($content['image']);
        self::assertArrayHasKey('preview', $content);
        self::assertNotEmpty($content['preview']);

        self::assertArrayHasKey('published_at', $content);
        self::assertEquals($post->published_at->format('Y-m-d\TH:i:sP'), $content['published_at']);

        /** @var \App\Modules\Blog\Models\Category $category */
        $category = $post->category()->get()->first();
        self::assertArrayHasKey('category', $content);
        self::assertIsArray($content['category']);
        self::assertArrayHasKey('id', $content['category']);
        self::assertEquals($category->id, $content['category']['id']);
        self::assertArrayHasKey('slug', $content['category']);
        self::assertEquals($category->slug, $content['category']['slug']);
        self::assertArrayHasKey('name', $content['category']);
        self::assertEquals($category->name, $content['category']['name']);
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD . 'not_found');
        $response->assertServerError();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
        self::assertEquals((new PostNotFoundException())->getCode(), $content['error']['code']);
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
