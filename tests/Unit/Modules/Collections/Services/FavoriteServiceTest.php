<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Collections\Services;

use App\Modules\Collections\Models\Collection;
use App\Modules\Collections\Models\Favorite;
use App\Modules\Collections\Models\File;
use App\Modules\Collections\Services\FavoriteService;
use App\Modules\Collections\Support\Blueprints\FavoriteBlueprint;
use App\Modules\Collections\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class FavoriteServiceTest extends TestCase
{
    private FavoriteService $favoriteService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->favoriteService = app(FavoriteService::class);
    }

    public function testSuccessfulGet()
    {
        /** @var Favorite $favorite */
        $favorite = Favorite::factory()->create();

        $results = $this->favoriteService->getFavorite($favorite->getKey());

        self::assertInstanceOf(Favorite::class, $results);
    }

    public function testSuccessfulGetBySlug()
    {
        /** @var Favorite $favorite */
        $favorite = Favorite::factory()->create();

        $results = $this->favoriteService->getFavoriteBySlug($favorite->slug);

        self::assertInstanceOf(Favorite::class, $results);
    }

    public function testSuccessfulGetList()
    {
        Favorite::factory(5)->create();

        $pagination = new Pagination(1, 4);
        $results = $this->favoriteService->getFavorites($pagination);

        self::assertInstanceOf(LengthAwarePaginator::class, $results);
        self::assertCount(4, $results->items());
    }

    public function testSuccessfulGetListEmpty()
    {
        Favorite::factory(5)->create([
            'collection_id' => Collection::factory()->create(['is_active' => false])
        ]);

        $pagination = new Pagination(1, 4);
        $results = $this->favoriteService->getFavorites($pagination);

        self::assertInstanceOf(LengthAwarePaginator::class, $results);
        self::assertCount(0, $results->items());
    }

    public function testSuccessfulCreate()
    {
        /** @var Collection $collection */
        $collection = Collection::factory()->create();

        /** @var File $file */
        $file = File::factory()->create();

        $data = new FavoriteBlueprint(
            fake()->slug(),
            'Collection 1',
            'Description more',
            '#000000'
        );

        $results = $this->favoriteService->createFavorite(
            $data,
            $collection,
            $file,
            $file
        );

        self::assertInstanceOf(Favorite::class, $results);
    }

    public function testSuccessfulUpdate()
    {
        /** @var Favorite $favorite */
        $favorite = Favorite::factory()->create();

        /** @var Collection $collection */
        $collection = Collection::factory()->create();

        /** @var File $file */
        $file = File::factory()->create();

        $data = new FavoriteBlueprint(
            fake()->slug(),
            'Favorite Collection 1',
            'Description more',
            '#000000'
        );

        $results = $this->favoriteService->updateFavorite(
            $favorite,
            $data,
            $collection,
            $file,
            $file
        );

        self::assertInstanceOf(Favorite::class, $results);
        self::assertEquals('Favorite Collection 1', $results->name);
        self::assertEquals('Description more', $results->description);
    }

    public function testSuccessfulDelete()
    {
        /** @var Favorite $favorite */
        $favorite = Favorite::factory()->create();

        $this->favoriteService->deleteFavorite($favorite->getKey());

        self::assertModelMissing($favorite);
    }

    public function testFailureGet()
    {
        /** @var Favorite $favorite */
        $favorite = Favorite::factory()->create([
            'collection_id' => Collection::factory()->create(['is_active' => false])
        ]);

        $result = $this->favoriteService->getFavorite($favorite->getKey());

        self::assertEmpty($result);
    }

    public function testFailureGetBySlug()
    {
        /** @var Favorite $favorite */
        $favorite = Favorite::factory()->create([
            'collection_id' => Collection::factory()->create(['is_active' => false])
        ]);

        $result = $this->favoriteService->getFavoriteBySlug($favorite->slug);

        self::assertEmpty($result);
    }
}
