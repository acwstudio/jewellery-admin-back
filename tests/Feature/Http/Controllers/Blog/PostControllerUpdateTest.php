<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Blog;

use App\Modules\Blog\Models\Category;
use App\Modules\Blog\Models\Post;
use App\Modules\Storage\Models\File;
use App\Modules\Storage\Models\Media;
use App\Modules\Users\Models\User;
use App\Packages\Enums\PostStatusEnum;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\Exceptions\Blog\CategoryNotFoundException;
use App\Packages\Exceptions\Blog\PostNotFoundException;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostControllerUpdateTest extends TestCase
{
    private const METHOD = '/api/v1/blog/post';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
        Storage::fake();
        Sanctum::actingAs($this->admin);
    }

    public function testSuccessful()
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        /** @var Category $currentCategory */
        $currentCategory = $post->category()->get()->first();

        $response = $this->put(self::METHOD, [
            'id' => $post->id,
            'category_id' => $currentCategory->id,
            'slug' => $post->slug,
            'status' => PostStatusEnum::DRAFT->value,
            'title' => 'Новый Пост 1',
            'content' => 'Текст контента (изменен)'
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);
        $post->refresh();

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertEquals($post->id, $content['id']);
        self::assertArrayHasKey('title', $content);
        self::assertEquals('Новый Пост 1', $content['title']);
        self::assertEquals($post->title, $content['title']);
        self::assertArrayHasKey('content', $content);
        self::assertEquals('Текст контента (изменен)', $content['content']);
        self::assertEquals($post->content, $content['content']);

        /** @var Category $category */
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

    public function testSuccessfulCategory()
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        /** @var Category $currentCategory */
        $currentCategory = $post->category()->get()->first();

        /** @var Category $newCategory */
        $newCategory = Category::factory()->create();

        $response = $this->put(self::METHOD, [
            'id' => $post->id,
            'category_id' => $newCategory->id,
            'slug' => $post->slug,
            'status' => PostStatusEnum::DRAFT->value,
            'title' => 'Новый Пост 1',
            'content' => 'Текст контента (изменен)'
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);
        $post->refresh();

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertEquals($post->id, $content['id']);

        /** @var Category $category */
        $category = $post->category()->get()->first();
        self::assertArrayHasKey('category', $content);
        self::assertIsArray($content['category']);

        self::assertArrayHasKey('id', $content['category']);
        self::assertEquals($category->id, $content['category']['id']);
        self::assertEquals($newCategory->id, $content['category']['id']);
        self::assertNotEquals($currentCategory->id, $category->id);

        self::assertArrayHasKey('slug', $content['category']);
        self::assertEquals($category->slug, $content['category']['slug']);
        self::assertArrayHasKey('name', $content['category']);
        self::assertEquals($category->name, $content['category']['name']);
    }

    public function testSuccessfulRelatedPosts()
    {
        /** @var \Illuminate\Database\Eloquent\Collection $relatedPosts */
        $relatedPosts = Post::factory(2)
            ->create();

        /** @var Post $post */
        $post = Post::factory()->create();

        /** @var Category $currentCategory */
        $currentCategory = $post->category()->get()->first();

        $response = $this->put(self::METHOD, [
            'id' => $post->id,
            'category_id' => $currentCategory->id,
            'slug' => $post->slug,
            'status' => PostStatusEnum::DRAFT->value,
            'title' => 'Новый Пост 1',
            'content' => 'Текст контента (изменен)',
            'related_posts' => $relatedPosts->pluck('id')->toArray()
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);
        $post->refresh();

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertEquals($post->id, $content['id']);
        self::assertArrayHasKey('title', $content);
        self::assertEquals('Новый Пост 1', $content['title']);
        self::assertEquals($post->title, $content['title']);
        self::assertArrayHasKey('content', $content);
        self::assertEquals('Текст контента (изменен)', $content['content']);
        self::assertEquals($post->content, $content['content']);

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
        /** @var Post $post */
        $post = Post::factory()->create();

        /** @var Category $currentCategory */
        $currentCategory = $post->category()->get()->first();

        $data = [
            'id' => $post->id,
            'category_id' => $currentCategory->id,
            'slug' => $post->slug,
            'status' => PostStatusEnum::DRAFT->value,
            'image_id' => self::createFile()->id,
            'preview_id' => self::createFile()->id,
            'title' => 'Новый Пост 1',
            'content' => 'Текст контента (изменен)'
        ];

        $response = $this->put(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);

        self::assertArrayHasKey('image', $content);
        self::assertNotEmpty($content['image']);
        self::assertArrayHasKey('id', $content['image']);
        self::assertArrayHasKey('file_name', $content['image']);
        self::assertArrayHasKey('url', $content['image']);
        self::assertArrayHasKey('type', $content['image']);

        self::assertArrayHasKey('preview', $content);
        self::assertNotEmpty($content['preview']);
        self::assertArrayHasKey('id', $content['preview']);
        self::assertArrayHasKey('file_name', $content['preview']);
        self::assertArrayHasKey('url', $content['preview']);
        self::assertArrayHasKey('type', $content['preview']);
    }

    public function testSuccessfulImageAndPreviewDeleteFile()
    {
        /** @var Post $post */
        $post = Post::factory()->create();
        $file1 = self::createFile();
        $file2 = self::createFile();

        /** @var Category $currentCategory */
        $currentCategory = $post->category()->get()->first();

        $data = [
            'id' => $post->id,
            'category_id' => $currentCategory->id,
            'slug' => $post->slug,
            'status' => PostStatusEnum::PUBLISHED->value,
            'image_id' => $file1->id,
            'preview_id' => $file2->id,
            'title' => 'Новый Пост 1',
            'content' => 'Текст контента (изменен)'
        ];

        $response = $this->put(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);

        self::assertArrayHasKey('image', $content);
        self::assertNotEmpty($content['image']);
        self::assertArrayHasKey('preview', $content);
        self::assertNotEmpty($content['preview']);

        $response = $this->delete('/api/v1/storage/file/' . $file1->id);
        $response->assertSuccessful();

        $response = $this->get('/api/v1/blog/post/' . $post->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('image', $content);
        self::assertEmpty($content['image']);
        self::assertArrayHasKey('preview', $content);
        self::assertNotEmpty($content['preview']);
    }

    public function testFailure()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->put(self::METHOD, [
            'id' => 1,
            'category_id' => $category->id,
            'slug' => 'новый пост',
            'status' => PostStatusEnum::DRAFT->value,
            'title' => 'Новый Пост 1',
            'content' => 'Текст контента'
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
        self::assertEquals((new PostNotFoundException())->getCode(), $content['error']['code']);
    }

    public function testFailureCategoryNotFound()
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        $response = $this->put(self::METHOD, [
            'id' => $post->id,
            'category_id' => 15,
            'slug' => 'новый пост',
            'status' => PostStatusEnum::DRAFT->value,
            'title' => 'Новый Пост 1',
            'content' => 'Текст контента'
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
        /** @var Post $postOne */
        $postOne = Post::factory()->create();

        /** @var Post $postTwo */
        $postTwo = Category::factory()->create();

        $response = $this->put(self::METHOD, [
            'id' => $postOne->id,
            'slug' => $postTwo->slug,
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

        /** @var Post $post */
        $post = Post::factory()->create();

        /** @var Category $currentCategory */
        $currentCategory = $post->category()->get()->first();

        $response = $this->put(self::METHOD, [
            'id' => $post->id,
            'category_id' => $currentCategory->id,
            'slug' => $post->slug,
            'status' => PostStatusEnum::DRAFT->value,
            'title' => 'Новый Пост 1',
            'content' => 'Текст контента (изменен)'
        ]);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
    }

    private static function createFile(): File
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
