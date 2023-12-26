<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Contracts\Pipelines\FeatureQueryBuilderPipelineContract;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Support\Blueprints\FeatureBlueprint;
use App\Modules\Catalog\Support\Filters\FeatureFilter;
use App\Modules\Catalog\Support\Pagination;
use App\Packages\Support\FilterQuery\FilterQueryBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FeatureRepository
{
    public function getById(int $id, bool $fail = false): ?Feature
    {
        if ($fail) {
            return Feature::findOrFail($id);
        }

        return Feature::find($id);
    }

    public function getByIds(array $ids, bool $fail = false): Collection
    {
        $models = Feature::query()->whereIn('id', $ids)->get();

        if ($fail && $models->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $models;
    }

    public function getList(Pagination $pagination, bool $fail = false): LengthAwarePaginator
    {
        $pagination = $this->setDefaultPagination($pagination);

        $query = Feature::query();

        /** @var FeatureQueryBuilderPipelineContract $pipeline */
        $pipeline = app(FeatureQueryBuilderPipelineContract::class);

        /** @var LengthAwarePaginator $models */
        $models = $pipeline
            ->send($query)
            ->thenReturn()
            ->paginate($pagination->perPage, ['*'], 'page', $pagination->page);

        if ($fail && $models->total() === 0) {
            throw new ModelNotFoundException();
        }

        return $models;
    }

    /**
     * @param FeatureFilter $filter
     * @param bool $fail
     * @return Collection<Feature>
     */
    public function getCollectionByFilter(FeatureFilter $filter, bool $fail = false): Collection
    {
        $query = FilterQueryBuilder::fromQuery(Feature::query())->withFilter($filter)->create();

        /** @var Collection<Feature> $models */
        $models = $query->get();

        if ($fail && $models->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $models;
    }

    public function create(FeatureBlueprint $featureBlueprint): Feature
    {
        $feature = new Feature([
            'type' => $featureBlueprint->getType(),
            'value' => $featureBlueprint->getValue(),
            'slug' => $featureBlueprint->getSlug(),
            'position' => $featureBlueprint->getPosition()
        ]);

        $feature->save();

        return $feature;
    }

    public function update(Feature $feature, FeatureBlueprint $featureBlueprint): void
    {
        $feature->update([
            'type' => $featureBlueprint->getType(),
            'value' => $featureBlueprint->getValue(),
            'slug' => $featureBlueprint->getSlug(),
            'position' => $featureBlueprint->getPosition()
        ]);
    }

    public function delete(Feature $feature): void
    {
        $feature->delete();
    }

    private function setDefaultPagination(Pagination $pagination): Pagination
    {
        $page = $pagination->page ?? 1;
        $perPage = $pagination->perPage ?? 32;

        return new Pagination($page, $perPage);
    }
}
