<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Category;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Storage\Models\Media;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/category';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testCreateCategory()
    {
        /** @var Category $parent */
        $parent = Category::factory()->create();

        $response = $this->post(self::METHOD, [
            'title' => fake()->text(50),
            'h1' => fake()->text(50),
            'description' => fake()->text(50),
            'meta_title' => fake()->text(50),
            'meta_description' => fake()->text(50),
            'meta_keywords' => fake()->text(50),
            'parent_id' => $parent->getKey(),
            'external_id' => fake()->sha1,
            'slug' => fake()->slug()
        ]);

        $response
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($parent) {
                $json
                    ->hasAll([
                        'id',
                        'title',
                        'h1',
                        'description',
                        'meta_title',
                        'meta_description',
                        'meta_keywords',
                        'created_at',
                        'updated_at',
                        'parent_id',
                        'external_id',
                        'children',
                        'slug',
                        'slug_aliases',
                        'preview_image'
                    ])
                    ->where('parent_id', $parent->getKey());
            });
    }

    /** TODO Пересмотреть тест */
    public function testCreateCategoryNotOrphanRoot()
    {
        /** @var Category $parent */
        $parent = Category::factory()->create();

        $response = $this->post(self::METHOD, [
            'title' => fake()->text(50),
            'h1' => fake()->text(50),
            'description' => fake()->text(50),
            'meta_title' => fake()->text(50),
            'meta_description' => fake()->text(50),
            'meta_keywords' => fake()->text(50),
            'parent_id' => $parent->getKey(),
            'external_id' => fake()->sha1,
            'slug' => fake()->slug()
        ]);

        $response->assertSuccessful();
    }

    public function testCreateCategoryNullSlug()
    {
        /** @var Category $parent */
        $parent = Category::factory()->create();

        $response = $this->post(self::METHOD, [
            'title' => fake()->text(50),
            'h1' => fake()->text(50),
            'description' => fake()->text(50),
            'meta_title' => fake()->text(50),
            'meta_description' => fake()->text(50),
            'meta_keywords' => fake()->text(50),
            'parent_id' => $parent->getKey(),
            'slug' => null
        ]);

        $response
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->whereNot('slug', null)
                    ->etc();
            });
    }

    public function testSuccessfulByPreviewImage()
    {
        $previewImage = $this->getPreviewImage();

        $response = $this->post(self::METHOD, [
            'title' => fake()->text(50),
            'h1' => fake()->text(50),
            'description' => fake()->text(50),
            'meta_title' => fake()->text(50),
            'meta_description' => fake()->text(50),
            'meta_keywords' => fake()->text(50),
            'external_id' => fake()->sha1,
            'slug' => fake()->slug(),
            'preview_image_id' => $previewImage->getKey()
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('preview_image', $content);
        self::assertIsArray($content['preview_image']);
        self::assertArrayHasKey('id', $content['preview_image']);
        self::assertEquals($previewImage->getKey(), $content['preview_image']['id']);
    }

    private function getPreviewImage(): PreviewImage
    {
        /** @var PreviewImage $previewImage */
        $previewImage = PreviewImage::factory()->create();
        Media::factory()->create([
            'model_type' => PreviewImage::class,
            'model_id' => $previewImage->getKey()
        ]);

        return $previewImage;
    }
}
