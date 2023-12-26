<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Collections\Services;

use App\Modules\Collections\Models\Collection;
use App\Modules\Collections\Services\CollectionService;
use App\Modules\Collections\Support\Pagination;
use App\Packages\DataObjects\Collections\Collection\CreateCollectionData;
use App\Packages\DataObjects\Collections\Collection\UpdateCollectionData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class CollectionServiceTest extends TestCase
{
    private CollectionService $collectionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->collectionService = app(CollectionService::class);
    }

    public function testSuccessfulGet()
    {
        /** @var Collection $collection */
        $collection = Collection::factory()->create();

        $results = $this->collectionService->getCollection($collection->getKey());

        self::assertInstanceOf(Collection::class, $results);
    }

    public function testSuccessfulGetBySlug()
    {
        /** @var Collection $collection */
        $collection = Collection::factory()->create();

        $results = $this->collectionService->getCollectionBySlug($collection->slug);

        self::assertInstanceOf(Collection::class, $results);
    }

    public function testSuccessfulGetList()
    {
        Collection::factory(5)->create();

        $pagination = new Pagination(1, 4);
        $results = $this->collectionService->getCollections($pagination);

        self::assertInstanceOf(LengthAwarePaginator::class, $results);
        self::assertCount(4, $results->items());
    }

    public function testSuccessfulGetListEmpty()
    {
        Collection::factory(5)->create(['is_active' => false]);

        $pagination = new Pagination(1, 4);
        $results = $this->collectionService->getCollections($pagination);

        self::assertInstanceOf(LengthAwarePaginator::class, $results);
        self::assertCount(0, $results->items());
    }

    public function testSuccessfulCreate()
    {
        $data = new CreateCollectionData(
            slug: fake()->slug(),
            name: 'Collection 1',
            description: 'Description more',
            is_active: true,
            is_hidden: false
        );

        $results = $this->collectionService->createCollection($data);

        self::assertInstanceOf(Collection::class, $results);
    }

    public function testSuccessfulUpdate()
    {
        /** @var Collection $collection */
        $collection = Collection::factory()->create();

        $data = new UpdateCollectionData(
            id: $collection->id,
            slug: fake()->slug(),
            name: 'Collection 1',
            description: 'Description more',
            is_active: true,
            is_hidden: false
        );

        $results = $this->collectionService->updateCollection(
            $collection,
            $data
        );

        self::assertInstanceOf(Collection::class, $results);
        self::assertEquals('Collection 1', $results->name);
        self::assertEquals('Description more', $results->description);
    }

    public function testSuccessfulDelete()
    {
        /** @var Collection $collection */
        $collection = Collection::factory()->create();

        $this->collectionService->deleteCollection($collection->getKey());

        self::assertModelMissing($collection);
    }

    public function testFailureGet()
    {
        /** @var Collection $collection */
        $collection = Collection::factory()->create(['is_active' => false]);

        $result = $this->collectionService->getCollection($collection->getKey());

        self::assertEmpty($result);
    }

    public function testFailureGetBySlug()
    {
        /** @var Collection $collection */
        $collection = Collection::factory()->create(['is_active' => false]);

        $result = $this->collectionService->getCollectionBySlug($collection->slug);

        self::assertEmpty($result);
    }
}
