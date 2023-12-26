<?php

declare(strict_types=1);

namespace App\Modules\Collections\Repository;

use App\Modules\Collections\Contracts\Pipelines\CollectionQueryBuilderPipelineContract;
use App\Modules\Collections\Models\File;
use App\Modules\Collections\Support\Filters\CollectionFilter;
use App\Modules\Collections\Support\Pagination;
use App\Modules\Collections\Models\Collection as CollectionModel;
use App\Packages\DataObjects\Collections\Collection\CreateCollectionData;
use App\Packages\DataObjects\Collections\Collection\UpdateCollectionData;
use App\Packages\Support\FilterQuery\FilterQueryBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CollectionRepository
{
    public function getById(int $id, bool $fail = false): ?CollectionModel
    {
        if ($fail) {
            return CollectionModel::findOrFail($id);
        }

        return CollectionModel::find($id);
    }

    public function getList(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $query = CollectionModel::query()->orderBy(Model::CREATED_AT, 'desc');

        /** @var CollectionQueryBuilderPipelineContract $pipeline */
        $pipeline = app(CollectionQueryBuilderPipelineContract::class);

        /** @var LengthAwarePaginator $collectionModels */
        $collectionModels = $pipeline
            ->send($query)
            ->thenReturn()
            ->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $collectionModels->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $collectionModels;
    }

    public function getAllList(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $query = CollectionModel::query();

        $paginator = $query->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $paginator->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $paginator;
    }

    /**
     * @param CollectionFilter $filter
     * @param bool $fail
     * @return Collection<CollectionModel>
     */
    public function getCollectionByFilter(CollectionFilter $filter, bool $fail = false): Collection
    {
        $query = FilterQueryBuilder::fromQuery(CollectionModel::query())->withFilter($filter)->create();

        /** @var Collection<CollectionModel> $models */
        $models = $query->get();

        if ($fail && $models->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $models;
    }

    public function create(
        CreateCollectionData $data,
        ?File $previewImage = null,
        ?File $previewImageMob = null,
        ?File $bannerImage = null,
        ?File $bannerImageMob = null,
        ?File $extendedImage = null,
        array $stones = [],
        array $products = [],
        array $images = []
    ): CollectionModel {
        $payload = [
            'slug' => $data->slug,
            'name' => $data->name,
            'description' => $data->description,
            'extended_name' => $data->extended_name,
            'extended_description' => $data->extended_description,
            'is_active' => $data->is_active,
            'is_hidden' => $data->is_hidden
        ];

        if (null !== $data->external_id) {
            $payload['external_id'] = $data->external_id;
        }

        $collection = new CollectionModel($payload);

        $collection->previewImage()->associate($previewImage);
        $collection->previewImageMob()->associate($previewImageMob);
        $collection->bannerImage()->associate($bannerImage);
        $collection->bannerImageMob()->associate($bannerImageMob);
        $collection->extendedImage()->associate($extendedImage);
        $collection->save();

        $collection->stones()->attach($stones);
        $collection->products()->attach($products);
        $collection->images()->attach($images);

        return $collection;
    }

    public function update(
        CollectionModel $collection,
        UpdateCollectionData $data,
        ?File $previewImage = null,
        ?File $previewImageMob = null,
        ?File $bannerImage = null,
        ?File $bannerImageMob = null,
        ?File $extendedImage = null,
        array $stones = [],
        array $products = [],
        array $images = []
    ): CollectionModel {
        $payload = [
            'slug' => $data->slug,
            'name' => $data->name,
            'description' => $data->description,
            'extended_name' => $data->extended_name,
            'extended_description' => $data->extended_description,
            'is_active' => $data->is_active,
            'is_hidden' => $data->is_hidden
        ];

        if (null !== $data->external_id) {
            $payload['external_id'] = $data->external_id;
        }

        $collection->update($payload);

        $collection->previewImage()->associate($previewImage);
        $collection->previewImageMob()->associate($previewImageMob);
        $collection->bannerImage()->associate($bannerImage);
        $collection->bannerImageMob()->associate($bannerImageMob);
        $collection->extendedImage()->associate($extendedImage);
        $collection->save();

        $collection->stones()->sync($stones);
        $collection->products()->sync($products);
        $collection->images()->sync($images);

        return $collection->refresh();
    }

    public function delete(CollectionModel $collection): void
    {
        $collection->delete();
    }
}
