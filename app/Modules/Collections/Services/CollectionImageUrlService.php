<?php

declare(strict_types=1);

namespace App\Modules\Collections\Services;

use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Modules\Collections\Models\CollectionImageUrl;
use App\Modules\Collections\Repository\CollectionImageUrlRepository;
use App\Modules\Collections\Repository\CollectionRepository;
use App\Packages\DataObjects\Collections\CollectionImageUrl\CreateCollectionImageUrlData;
use App\Packages\DataObjects\Collections\CollectionImageUrl\UpdateCollectionImageUrlData;
use Illuminate\Support\Collection;

class CollectionImageUrlService
{
    public function __construct(
        private readonly CollectionImageUrlRepository $collectionImageUrlRepository,
        private readonly CollectionRepository $collectionRepository
    ) {
    }

    public function getById(int $id): ?CollectionImageUrl
    {
        return $this->collectionImageUrlRepository->getById($id);
    }

    public function create(CreateCollectionImageUrlData $data, CollectionModel|int $collection): CollectionImageUrl
    {
        if (is_int($collection)) {
            $collection = $this->collectionRepository->getById($collection, true);
        }

        return $this->collectionImageUrlRepository->create($data, $collection);
    }

    public function update(
        CollectionImageUrl|int $collectionImageUrl,
        UpdateCollectionImageUrlData $data,
        CollectionModel|int|null $collection = null
    ): CollectionImageUrl {
        if (is_int($collectionImageUrl)) {
            $collectionImageUrl = $this->collectionImageUrlRepository->getById($collectionImageUrl, true);
        }

        if (is_int($collection)) {
            $collection = $this->collectionRepository->getById($collection, true);
        }

        $this->collectionImageUrlRepository->update($collectionImageUrl, $data, $collection);

        return $collectionImageUrl->refresh();
    }

    public function delete(CollectionImageUrl|int $collectionImageUrl): void
    {
        if (is_int($collectionImageUrl)) {
            $collectionImageUrl = $this->collectionImageUrlRepository->getById($collectionImageUrl, true);
        }
        $this->collectionImageUrlRepository->delete($collectionImageUrl);
    }
}
