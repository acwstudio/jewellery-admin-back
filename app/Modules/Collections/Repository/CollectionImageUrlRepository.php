<?php

declare(strict_types=1);

namespace App\Modules\Collections\Repository;

use App\Modules\Collections\Models\CollectionImageUrl;
use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Packages\DataObjects\Collections\CollectionImageUrl\CreateCollectionImageUrlData;
use App\Packages\DataObjects\Collections\CollectionImageUrl\UpdateCollectionImageUrlData;

class CollectionImageUrlRepository
{
    public function getById(int $id, bool $fail = false): ?CollectionImageUrl
    {
        if ($fail) {
            return CollectionImageUrl::findOrFail($id);
        }

        return CollectionImageUrl::find($id);
    }

    public function create(CreateCollectionImageUrlData $data, CollectionModel $collection): CollectionImageUrl
    {
        $model = new CollectionImageUrl([
            'path' => $data->path,
            'type' => $data->type
        ]);

        $model->collection()->associate($collection);
        $model->save();

        return $model;
    }

    public function update(
        CollectionImageUrl $model,
        UpdateCollectionImageUrlData $data,
        ?CollectionModel $collection = null
    ): void {
        if (null !== $collection) {
            $model->collection()->associate($collection);
        }

        $model->update([
            'path' => $data->path,
            'type' => $data->type
        ]);
    }

    public function delete(CollectionImageUrl $model): void
    {
        $model->delete();
    }
}
