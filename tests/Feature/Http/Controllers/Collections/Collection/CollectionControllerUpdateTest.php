<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\Collection;

use App\Modules\Catalog\Models\Product;
use App\Modules\Collections\Models\Collection;
use App\Modules\Collections\Models\File;
use App\Modules\Collections\Models\Stone;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class CollectionControllerUpdateTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/collections/collection/';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(
            $this->getUser(RoleEnum::ADMIN)
        );
    }

    public function testSuccessful()
    {
        /** @var Collection $collection */
        $collection = Collection::factory()->create();

        /** @var File $file */
        $file = $this->createFiles(1)->first();

        /** @var Stone $stone */
        $stone = Stone::factory()->create();

        /** @var Product $product */
        $product = Product::factory()->create(['setFull' => true]);

        $response = $this->put(self::METHOD . $collection->getKey(), [
            'slug' => fake()->slug(),
            'name' => fake()->text(50),
            'description' => fake()->text(50),
            'preview_image_id' => $file->getKey(),
            'preview_image_mob_id' => $file->getKey(),
            'banner_image_id' => $file->getKey(),
            'banner_image_mob_id' => $file->getKey(),
            'stones' => [$stone->getKey()],
            'products' => [$product->getKey()],
            'is_active' => true,
            'is_hidden' => false
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('slug', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('description', $content);
        self::assertArrayHasKey('preview_image', $content);
        self::assertArrayHasKey('preview_image_mob', $content);
        self::assertArrayHasKey('banner_image', $content);
        self::assertArrayHasKey('banner_image_mob', $content);
        self::assertArrayHasKey('stones', $content);
        self::assertIsArray($content['stones']);
        self::assertNotEmpty($content['stones']);
        self::assertCount(1, $content['stones']);
        self::assertArrayHasKey('products', $content);
        self::assertIsArray($content['products']);
        self::assertNotEmpty($content['products']);
        self::assertCount(1, $content['products']);
        self::assertArrayHasKey('images', $content);
        self::assertIsArray($content['images']);
        self::assertEmpty($content['images']);
        self::assertArrayHasKey('extended_image', $content);
        self::assertArrayHasKey('extended_name', $content);
        self::assertArrayHasKey('extended_description', $content);
    }

    public function testFailure()
    {
        $response = $this->put(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
