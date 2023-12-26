<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\Collection;

use App\Modules\Catalog\Models\Product;
use App\Modules\Collections\Enums\CollectionImageUrlTypeEnum;
use App\Modules\Collections\Models\Collection;
use App\Modules\Collections\Models\CollectionImageUrl;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class CollectionControllerGetTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/collections/collection/';

    public function testSuccessful()
    {
        /** @var \App\Modules\Collections\Models\Collection $collection */
        $collection = $this->createCollections(1)->first();

        $response = $this->get(self::METHOD . $collection->slug);
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
        self::assertArrayHasKey('products', $content);
        self::assertIsArray($content['products']);
        self::assertArrayHasKey('categories', $content);
        self::assertIsArray($content['categories']);
        self::assertArrayHasKey('images', $content);
        self::assertIsArray($content['images']);
        self::assertArrayHasKey('extended_image', $content);
        self::assertArrayHasKey('extended_name', $content);
        self::assertArrayHasKey('extended_description', $content);
    }

    public function testSuccessfulProductsLimit()
    {
        $products = Product::factory(20)->create(['setFull' => true]);

        /** @var \App\Modules\Collections\Models\Collection $collection */
        $collection = $this->createCollections(1)->first();
        $collection->products()->sync($products);

        $response = $this->get(self::METHOD . $collection->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('products', $content);
        self::assertIsArray($content['products']);
        self::assertCount(config('collections.products.limit'), $content['products']);
    }

    public function testSuccessfulProductsRandom()
    {
        $products = Product::factory(20)->create(['setFull' => true]);

        $products = $products->random(10);

        /** @var \App\Modules\Collections\Models\Collection $collection */
        $collection = $this->createCollections(1)->first();
        $collection->products()->sync($products);

        $response = $this->get(self::METHOD . $collection->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('products', $content);
        self::assertIsArray($content['products']);
    }

    public function testSuccessfulByImageUrls()
    {
        /** @var \App\Modules\Collections\Models\Collection $collection */
        $collection = $this->createCollections(1)->first();
        $this->createImageUrls($collection);

        $response = $this->get(self::METHOD . $collection->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('preview_image', $content);
        self::assertArrayHasKey('preview_image_mob', $content);
        self::assertArrayHasKey('banner_image', $content);
        self::assertArrayHasKey('banner_image_mob', $content);
        self::assertArrayHasKey('extended_image', $content);
    }

    public function testSuccessfulEmpty()
    {
        $response = $this->get(self::METHOD . fake()->slug());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertEmpty($content);
    }

    private function createImageUrls(Collection $collection): void
    {
        foreach (CollectionImageUrlTypeEnum::cases() as $type) {
            CollectionImageUrl::factory()->create([
                'collection_id' => $collection,
                'type' => $type
            ]);
        }

        $collection->refresh();
    }
}
