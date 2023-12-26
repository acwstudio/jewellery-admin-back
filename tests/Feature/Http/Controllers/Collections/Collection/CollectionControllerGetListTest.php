<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Collections\Collection;

use App\Modules\Catalog\Models\Product;
use App\Modules\Collections\Models\Collection;
use App\Modules\Collections\Models\Favorite;
use App\Modules\Collections\Models\Stone;
use Illuminate\Support\Facades\Config;
use Tests\Feature\Http\Controllers\Collections\CollectionTestCase;

class CollectionControllerGetListTest extends CollectionTestCase
{
    private const METHOD = '/api/v1/collections/collection';

    public function testSuccessful()
    {
        $this->createCollections(5);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(5, $content['items']);
    }

    public function testSuccessfulIsActive()
    {
        $collections = $this->createCollections(5);

        /** @var \App\Modules\Collections\Models\Collection $collection */
        $collection = $collections->random();
        $collection->update(['is_active' => false]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(4, $content['items']);
    }

    public function testSuccessfulIsHidden()
    {
        $collections = $this->createCollections(5);

        /** @var \App\Modules\Collections\Models\Collection $collection */
        $collection = $collections->random();
        $collection->update(['is_hidden' => true]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(4, $content['items']);
    }

    public function testSuccessfulEmpty()
    {
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertEmpty($content['items']);
    }

    public function testSuccessfulFilterStone()
    {
        $collections = $this->createCollections(5);

        /** @var Stone $stone */
        $stone = Stone::factory()->create();

        /** @var \App\Modules\Collections\Models\Collection $collection */
        $collection = $collections->random();

        $collection->stones()->attach($stone->getKey());
        $collection->save();

        $response = $this->get(self::METHOD . "?filter[stone]={$stone->getKey()}");
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(1, $content['items']);
    }

    public function testSuccessfulFilterName()
    {
        $searchName = 'найденное';

        $collections = $this->createCollections(5);
        $collections = $collections->random(2);

        /** @var \App\Modules\Collections\Models\Collection $collectionFirst */
        $collectionFirst = $collections->first();
        $collectionFirst->update(['name' => $collectionFirst->name . $searchName]);

        /** @var \App\Modules\Collections\Models\Collection $collectionSecond */
        $collectionSecond = $collections->last();
        $collectionSecond->update(['description' => $searchName . $collectionSecond->description]);

        $response = $this->get(self::METHOD . "?filter[name]={$searchName}");
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);
    }

    public function testSuccessfulFilterNameIsActive()
    {
        $searchName = 'найденное';

        $collections = $this->createCollections(5);
        $collections = $collections->random(2);

        /** @var \App\Modules\Collections\Models\Collection $collectionFirst */
        $collectionFirst = $collections->first();
        $collectionFirst->update(['name' => $collectionFirst->name . $searchName, 'is_active' => false]);

        /** @var \App\Modules\Collections\Models\Collection $collectionSecond */
        $collectionSecond = $collections->last();
        $collectionSecond->update(['description' => $searchName . $collectionSecond->description]);

        $response = $this->get(self::METHOD . "?filter[name]={$searchName}");
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(1, $content['items']);
    }

    public function testSuccessfulFavorite()
    {
        $collections = $this->createCollections(5);

        /** @var \App\Modules\Collections\Models\Collection $collection */
        $collection = $collections->random();

        Favorite::factory()->create(['collection_id' => $collection]);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(4, $content['items']);
    }

    public function testSuccessfulFavoriteName()
    {
        $searchName = 'найденное';

        $collections = $this->createCollections(5);
        $collections = $collections->random(2);

        /** @var \App\Modules\Collections\Models\Collection $collectionFirst */
        $collectionFirst = $collections->first();
        $collectionFirst->update(['name' => $collectionFirst->name . $searchName]);
        Favorite::factory()->create(['collection_id' => $collectionFirst]);

        /** @var \App\Modules\Collections\Models\Collection $collectionSecond */
        $collectionSecond = $collections->last();
        $collectionSecond->update(['description' => $searchName . $collectionSecond->description]);
        Favorite::factory()->create(['collection_id' => $collectionSecond]);

        $response = $this->get(self::METHOD . "?filter[name]={$searchName}");
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(2, $content['items']);
    }

    public function testSuccessfulProductLimit()
    {
        $products = Product::factory(10)->create(['setFull' => true]);

        /** @var Collection $collection */
        $collection = Collection::factory()->create();
        $collection->products()->attach($products);

        $notActiveProducts = $products->slice(0, 4);
        /** @var Product $product */
        foreach ($notActiveProducts as $product) {
            $product->imageUrls()->getQuery()->delete();
            $product->refresh();
        }

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(1, $content['items']);
        foreach ($content['items'] as $item) {
            self::assertArrayHasKey('products', $item);
            self::assertGreaterThanOrEqual(config('collections.products.limit'), $item['products']);
        }
    }

    public function testSuccessfulExcludeIds()
    {
        $collections = $this->createCollections(5);
        $excludeCollections = $collections->random(2);
        $ids = implode(',', $excludeCollections->pluck('id')->all());
        Config::set('collections.exclude_ids', $ids);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertIsArray($content['items']);
        self::assertCount(3, $content['items']);
    }
}
