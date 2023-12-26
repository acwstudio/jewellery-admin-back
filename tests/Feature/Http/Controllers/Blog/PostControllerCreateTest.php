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
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/blog/post';
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

        $response = $this->post(self::METHOD, [
            'category_id' => $category->id,
            'slug' => 'Пост One',
            'title' => 'Пост 1',
            'content' => 'Текст контента',
            'status' => PostStatusEnum::PUBLISHED->value,
            'published_at' => '2023-03-09T15:31:00+00:00',
            'meta_title' => 'SEO Заголовок',
            'meta_description' => 'SEO Описание'
        ]);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('slug', $content);
        self::assertEquals(Str::slug('Пост One'), $content['slug']);
        self::assertArrayHasKey('title', $content);
        self::assertEquals('Пост 1', $content['title']);
        self::assertArrayHasKey('content', $content);
        self::assertEquals('Текст контента', $content['content']);
        self::assertArrayHasKey('status', $content);
        self::assertEquals(PostStatusEnum::PUBLISHED->value, $content['status']);
        self::assertArrayHasKey('published_at', $content);
        self::assertEquals(
            (new Carbon('2023-03-09 15:31'))->format('Y-m-d\TH:i:sP'),
            $content['published_at']
        );
        self::assertArrayHasKey('meta_title', $content);
        self::assertEquals('SEO Заголовок', $content['meta_title']);
        self::assertArrayHasKey('meta_description', $content);
        self::assertEquals('SEO Описание', $content['meta_description']);
    }

    public function testSuccessfulTranslit()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->post(self::METHOD, [
            'category_id' => $category->id,
            'status' => PostStatusEnum::DRAFT->value,
            'slug' => ' Пост One ',
            'title' => 'Пост 1',
            'content' => 'Текст контента'
        ]);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('slug', $content);
        self::assertNotEquals('Пост One', $content['slug']);
        self::assertEquals(Str::slug('Пост One'), $content['slug']);
    }

    public function testSuccessfulRelatedPosts()
    {
        /** @var \Illuminate\Database\Eloquent\Collection $relatedPosts */
        $relatedPosts = Post::factory(2)->create(['status' => PostStatusEnum::PUBLISHED]);

        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->post(self::METHOD, [
            'category_id' => $category->id,
            'slug' => 'Пост One',
            'title' => 'Пост 1',
            'content' => 'Текст контента',
            'status' => PostStatusEnum::PUBLISHED->value,
            'published_at' => '2023-03-09T15:31:00+00:00',
            'related_posts' => $relatedPosts->pluck('id')->toArray()
        ]);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);

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

    public function testSuccessfulByImage()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $data = [
            'category_id' => $category->id,
            'slug' => 'Пост One',
            'title' => 'Пост 1',
            'content' => 'Текст контента',
            'status' => PostStatusEnum::PUBLISHED->value,
            'published_at' => '2023-03-09T15:31:00+00:00',
            'meta_title' => 'SEO Заголовок',
            'meta_description' => 'SEO Описание',
            'image_id' => self::createFile()->id,
            'preview_id' => self::createFile()->id
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertSuccessful();

        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);

        self::assertArrayHasKey('image', $content);
        self::assertArrayHasKey('id', $content['image']);
        self::assertArrayHasKey('file_name', $content['image']);
        self::assertArrayHasKey('url', $content['image']);
        self::assertArrayHasKey('type', $content['image']);

        self::assertArrayHasKey('preview', $content);
        self::assertArrayHasKey('id', $content['preview']);
        self::assertArrayHasKey('file_name', $content['preview']);
        self::assertArrayHasKey('url', $content['preview']);
        self::assertArrayHasKey('type', $content['preview']);
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

        /** @var Post $post */
        $post = Post::factory()->create();

        $response = $this->post(self::METHOD, [
            'category_id' => $category->id,
            'slug' => $post->slug,
            'status' => PostStatusEnum::DRAFT->value,
            'title' => 'Пост 1',
            'content' => 'Текст контента'
        ]);
        $response->assertServerError();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
    }

    public function testFailureCategoryNotFound()
    {
        $response = $this->post(self::METHOD, [
            'category_id' => 52,
            'slug' => 'новый пост',
            'status' => PostStatusEnum::DRAFT->value,
            'title' => 'Пост 1',
            'content' => 'Текст контента'
        ]);
        $response->assertServerError();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertArrayHasKey('message', $content['error']);
        self::assertEquals((new CategoryNotFoundException())->getCode(), $content['error']['code']);
    }

    public function testFailureAccessDenied()
    {
        Sanctum::actingAs($this->getUser());

        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->post(self::METHOD, [
            'category_id' => $category->id,
            'slug' => 'Пост One',
            'title' => 'Пост 1',
            'content' => 'Текст контента',
            'status' => PostStatusEnum::PUBLISHED->value,
            'published_at' => '2023-03-09T15:31:00+00:00',
            'meta_title' => 'SEO Заголовок',
            'meta_description' => 'SEO Описание'
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

        return $file->refresh();
    }
}
