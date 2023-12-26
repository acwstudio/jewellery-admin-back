<?php

declare(strict_types=1);

namespace App\Modules\Collections\Services;

use App\Modules\Collections\Models\File;
use App\Modules\Collections\Repository\FileRepository;
use App\Modules\Collections\Repository\StoneRepository;
use App\Modules\Collections\Support\Filters\CollectionFilter;
use App\Modules\Collections\Support\Pagination;
use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Modules\Collections\Repository\CollectionRepository;
use App\Packages\DataObjects\Collections\Collection\CreateCollectionData;
use App\Packages\DataObjects\Collections\Collection\UpdateCollectionData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CollectionService
{
    public function __construct(
        private readonly CollectionRepository $collectionRepository,
        private readonly FileRepository $fileRepository,
        private readonly StoneRepository $stoneRepository
    ) {
    }

    public function getCollection(int $id, ?bool $isActive = true): ?CollectionModel
    {
        /** @var CollectionModel|null $model */
        $model = $this->collectionRepository->getCollectionByFilter(
            new CollectionFilter(
                id: new Collection([$id]),
                is_active: $isActive
            )
        )->first();

        return $model;
    }

    public function getCollectionBySlug(string $slug, ?bool $isActive = true): ?CollectionModel
    {
        /** @var CollectionModel|null $model */
        $model = $this->collectionRepository->getCollectionByFilter(
            new CollectionFilter(
                slug: $slug,
                is_active: $isActive
            )
        )->first();

        return $model;
    }

    public function getByFilter(CollectionFilter $filter): Collection
    {
        return $this->collectionRepository->getCollectionByFilter($filter);
    }

    public function getCollections(Pagination $pagination): LengthAwarePaginator
    {
        return $this->collectionRepository->getList($pagination);
    }

    public function getAllCollections(Pagination $pagination): LengthAwarePaginator
    {
        return $this->collectionRepository->getAllList($pagination);
    }

    public function createCollection(
        CreateCollectionData $data,
        File|int|null $previewImage = null,
        File|int|null $previewImageMob = null,
        File|int|null $bannerImage = null,
        File|int|null $bannerImageMob = null,
        File|int|null $extendedImage = null,
        array $stones = [],
        array $products = [],
        array $images = []
    ): CollectionModel {
        if (is_int($previewImage)) {
            $previewImage = $this->fileRepository->getById($previewImage, true);
        }

        $this->getPreviewFiles(
            previewImage: $previewImage,
            previewImageMob: $previewImageMob,
            bannerImage: $bannerImage,
            bannerImageMob: $bannerImageMob,
            extendedImage: $extendedImage
        );

        $stones = $this->getStoneIds($stones);
        $images = $this->getImageArrayWithOrder($images);

        return $this->collectionRepository->create(
            $data,
            $previewImage,
            $previewImageMob,
            $bannerImage,
            $bannerImageMob,
            $extendedImage,
            $stones,
            $products,
            $images
        );
    }

    public function updateCollection(
        CollectionModel|int $collection,
        UpdateCollectionData $data,
        File|int|null $previewImage = null,
        File|int|null $previewImageMob = null,
        File|int|null $bannerImage = null,
        File|int|null $bannerImageMob = null,
        File|int|null $extendedImage = null,
        array $stones = [],
        array $products = [],
        array $images = []
    ): CollectionModel {
        if (is_int($collection)) {
            $collection = $this->collectionRepository->getById($collection, true);
        }

        $this->getPreviewFiles(
            previewImage: $previewImage,
            previewImageMob: $previewImageMob,
            bannerImage: $bannerImage,
            bannerImageMob: $bannerImageMob,
            extendedImage: $extendedImage
        );

        $stones = $this->getStoneIds($stones);
        $images = $this->getImageArrayWithOrder($images);

        return $this->collectionRepository->update(
            $collection,
            $data,
            $previewImage,
            $previewImageMob,
            $bannerImage,
            $bannerImageMob,
            $extendedImage,
            $stones,
            $products,
            $images
        );
    }

    public function deleteCollection(int $id): void
    {
        $collection = $this->collectionRepository->getById($id, true);
        $this->collectionRepository->delete($collection);
    }

    private function getStoneIds(array $ids): array
    {
        $stones = $this->stoneRepository->getByIds($ids);
        $stoneIds = [];
        foreach ($stones as $stone) {
            $stoneIds[] = $stone->getKey();
        }

        return $stoneIds;
    }

    private function getImageArrayWithOrder(array $ids): array
    {
        $images = $this->fileRepository->getByIds($ids);
        $imageIds = [];
        foreach ($images as $image) {
            $key = (int)array_search($image->getKey(), $ids);
            $imageIds[$image->getKey()] = ['order_column' => $key + 1];
        }

        return $imageIds;
    }

    private function getPreviewFiles(
        File|int|null &$previewImage = null,
        File|int|null &$previewImageMob = null,
        File|int|null &$bannerImage = null,
        File|int|null &$bannerImageMob = null,
        File|int|null &$extendedImage = null,
    ): void {
        if (is_int($previewImage)) {
            $previewImage = $this->getFile($previewImage);
        }

        if (is_int($previewImageMob)) {
            $previewImageMob = $this->getFile($previewImageMob);
        }

        if (is_int($bannerImage)) {
            $bannerImage = $this->getFile($bannerImage);
        }

        if (is_int($bannerImageMob)) {
            $bannerImageMob = $this->getFile($bannerImageMob);
        }

        if (is_int($extendedImage)) {
            $extendedImage = $this->getFile($extendedImage);
        }
    }

    private function getFile(int $id): File
    {
        return $this->fileRepository->getById($id, true);
    }
}
