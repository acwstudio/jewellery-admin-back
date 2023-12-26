<?php

declare(strict_types=1);

namespace App\Modules\Collections\Services;

use App\Modules\Collections\Models\Favorite;
use App\Modules\Collections\Models\File;
use App\Modules\Collections\Repository\FavoriteRepository;
use App\Modules\Collections\Repository\FileRepository;
use App\Modules\Collections\Support\Blueprints\FavoriteBlueprint;
use App\Modules\Collections\Support\Filters\FavoriteFilter;
use App\Modules\Collections\Support\Pagination;
use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Modules\Collections\Repository\CollectionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FavoriteService
{
    public function __construct(
        private readonly FavoriteRepository $favoriteRepository,
        private readonly CollectionRepository $collectionRepository,
        private readonly FileRepository $fileRepository
    ) {
    }

    public function getFavorite(int $id, ?bool $collectionIsActive = true): ?Favorite
    {
        /** @var Favorite|null $model */
        $model = $this->favoriteRepository->getCollectionByFilter(
            new FavoriteFilter(id: new Collection([$id])),
            collectionIsActive: $collectionIsActive
        )->first();

        return $model;
    }

    public function getFavoriteBySlug(string $slug, ?bool $collectionIsActive = true): ?Favorite
    {
        /** @var Favorite|null $model */
        $model = $this->favoriteRepository->getCollectionByFilter(
            new FavoriteFilter(slug: $slug),
            collectionIsActive: $collectionIsActive
        )->first();

        return $model;
    }

    public function getFavorites(Pagination $pagination): LengthAwarePaginator
    {
        return $this->favoriteRepository->getList($pagination);
    }

    public function createFavorite(
        FavoriteBlueprint $favoriteBlueprint,
        CollectionModel|int $collection,
        File|int $image,
        File|int $imageMob
    ): Favorite {
        if (is_int($collection)) {
            $collection = $this->collectionRepository->getById($collection, true);
        }

        if (is_int($image)) {
            $image = $this->fileRepository->getById($image, true);
        }

        if (is_int($imageMob)) {
            $imageMob = $this->fileRepository->getById($imageMob, true);
        }

        return $this->favoriteRepository->create(
            $favoriteBlueprint,
            $collection,
            $image,
            $imageMob
        );
    }

    public function updateFavorite(
        Favorite|int $favorite,
        FavoriteBlueprint $favoriteBlueprint,
        CollectionModel|int $collection,
        File|int $image,
        File|int $imageMob
    ): Favorite {
        if (is_int($favorite)) {
            $favorite = $this->favoriteRepository->getById($favorite, true);
        }

        if (is_int($collection)) {
            $collection = $this->collectionRepository->getById($collection, true);
        }

        if (is_int($image)) {
            $image = $this->fileRepository->getById($image, true);
        }

        if (is_int($imageMob)) {
            $imageMob = $this->fileRepository->getById($imageMob, true);
        }

        return $this->favoriteRepository->update(
            $favorite,
            $favoriteBlueprint,
            $collection,
            $image,
            $imageMob
        );
    }

    public function deleteFavorite(int $id): void
    {
        $favorite = $this->favoriteRepository->getById($id, true);
        $this->favoriteRepository->delete($favorite);
    }
}
