<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Category;

use App\Modules\Catalog\Models\Category;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CategoryListControllerGetByIdTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/category_list/';

    public function testSuccessful()
    {
        $categories = Category::factory()
            ->count(2)
            ->has(
                Category::factory()->count(3),
                'children'
            )
            ->create();

        /** @var Category $firstCategory */
        $firstCategory = $categories->first();

        $queryParams = http_build_query([
            'with' => [
                'children'
            ]
        ]);

        $response = $this->get(self::METHOD . $firstCategory->getKey() . '?' . $queryParams);
        $response
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($firstCategory) {
                $json
                    ->where('id', $firstCategory->getKey())
                    ->hasAll(['id', 'parent_id', 'title', 'h1', 'children', 'slug', 'preview_image'])
                    ->has('children', 3, function (AssertableJson $json) {
                        $json->hasAll(['id', 'parent_id', 'title', 'h1', 'children', 'slug', 'preview_image']);
                    });
            });
    }
}
