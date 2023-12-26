<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Category;

use App\Modules\Catalog\Models\Category;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/category/';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
    }

    public function testGetCategory()
    {
        $category = Category::factory()->create();

        $response = $this->get(self::METHOD . $category->getKey());

        $response
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($category) {
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
                        'children',
                        'external_id',
                        'slug',
                        'slug_aliases',
                        'preview_image'
                    ])
                    ->where('id', $category->getKey());
            });
    }

    public function testDeleteCategory()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete(self::METHOD . $category->getKey());

        $response->assertSuccessful();
        $this->assertNull(
            Category::find($category->getKey())
        );
    }

    public function testGetCategoriesById()
    {
        $categories = Category::factory()->count(3)->create();

        $ids = $categories->slice(0, 2)->pluck('id');

        $response = $this->get('/api/v1/catalog/category?' . http_build_query(['id' => $ids->toArray()]));

        $response
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($ids) {
                $json
                    ->has(count($ids));
            });
    }

    public function testGetCategoriesByExternalId()
    {
        $categories = Category::factory()->count(3)->create();

        /** @var Category $firstCategory */
        $firstCategory = $categories->first();

        $response = $this->get(
            '/api/v1/catalog/category?' . http_build_query(['external_id' => $firstCategory->external_id])
        );

        $response
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($firstCategory) {
                $json
                    ->has(1)
                    ->first(function (AssertableJson $json) use ($firstCategory) {
                        $json
                            ->has('id')
                            ->where('id', $firstCategory->getKey())
                            ->etc();
                    });
            });
    }

    public function testCreateCategorySlugAlias()
    {
        $category = Category::factory()->create();

        $slug = fake()->slug();

        $response = $this->actingAs($this->admin)->post(
            route('api.v1.catalog.category.slug.create', $category->getKey()),
            ['slug' => $slug]
        );

        $response->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($slug) {
                $json->hasAll(['id', 'slug'])
                    ->where('slug', $slug);
            });
    }

    public function testGetCategoryBySlug()
    {
        /** @var Category $category */
        $category = Category::factory()->create();

        $response = $this->get(route('api.v1.catalog.category.slug.get', $category->slug));

        $response
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($category) {
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
                        'children',
                        'external_id',
                        'slug',
                        'slug_aliases',
                        'preview_image'
                    ])
                    ->where('id', $category->getKey());
            });
    }
}
