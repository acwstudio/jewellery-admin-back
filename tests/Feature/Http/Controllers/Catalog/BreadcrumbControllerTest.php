<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog;

use App\Modules\Catalog\Models\Category;
use Tests\TestCase;

class BreadcrumbControllerTest extends TestCase
{
    public function testGetBreadcrumbs()
    {
        // Category с 2 родителями
        /** @var Category $category */
        $category = Category::factory()->for(
            Category::factory()->for(
                Category::factory(),
                'parent'
            ),
            'parent'
        )->create();

        $response = $this->get(route('api.v1.catalog.category.breadcrumbs.get.id', $category->id));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertIsArray($content);
        self::assertArrayNotHasKey('error', $content);
        self::assertCount(3, $content);
        foreach ($content as $item) {
            self::assertArrayHasKey('category_id', $item);
            self::assertArrayHasKey('parent_id', $item);
            self::assertArrayHasKey('title', $item);
            self::assertArrayHasKey('slug', $item);
        }

        self::assertEquals($category->parent->parent->id, $content[0]['category_id']);
        self::assertEquals($category->id, $content[2]['category_id']);
    }

    public function testGetBreadcrumbsBySlug()
    {
        // Category с 2 родителями
        /** @var Category $category */
        $category = Category::factory()->for(
            Category::factory()->for(
                Category::factory(),
                'parent'
            ),
            'parent'
        )->create();

        $response = $this->get(route('api.v1.catalog.category.breadcrumbs.get.slug', $category->slug));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertIsArray($content);
        self::assertArrayNotHasKey('error', $content);
        self::assertCount(3, $content);
        foreach ($content as $item) {
            self::assertArrayHasKey('category_id', $item);
            self::assertArrayHasKey('parent_id', $item);
            self::assertArrayHasKey('title', $item);
            self::assertArrayHasKey('slug', $item);
        }

        self::assertEquals($category->parent->parent->id, $content[0]['category_id']);
        self::assertEquals($category->id, $content[2]['category_id']);
    }
}
