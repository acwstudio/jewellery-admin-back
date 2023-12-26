<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Repositories\FeatureRepository;
use App\Modules\Catalog\Support\Blueprints\FeatureBlueprint;
use App\Modules\Catalog\Support\Filters\FeatureFilter;
use App\Modules\Catalog\Support\Pagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FeatureService
{
    public function __construct(
        private readonly FeatureRepository $featureRepository
    ) {
    }

    public function getFeature(int $id): ?Feature
    {
        return $this->featureRepository->getById($id);
    }

    public function getFeatures(Pagination $pagination): LengthAwarePaginator
    {
        return $this->featureRepository->getList($pagination);
    }

    public function getFeatureCollectionByFilter(FeatureFilter $filter): Collection
    {
        return $this->featureRepository->getCollectionByFilter($filter);
    }

    public function createFeature(FeatureBlueprint $featureBlueprint): Feature
    {
        $this->generateSlug($featureBlueprint);

        return $this->featureRepository->create($featureBlueprint);
    }

    public function updateFeature(
        Feature|int $feature,
        FeatureBlueprint $featureBlueprint
    ): Feature {
        if (is_int($feature)) {
            $feature = $this->featureRepository->getById($feature, true);
        }

        $this->generateSlug($featureBlueprint);

        $this->featureRepository->update($feature, $featureBlueprint);

        return $feature->refresh();
    }

    public function deleteFeature(Feature|int $feature): void
    {
        if (is_int($feature)) {
            $feature = $this->featureRepository->getById($feature, true);
        }

        $this->featureRepository->delete($feature);
    }

    private function generateSlug(FeatureBlueprint $featureBlueprint): void
    {
        $slug = $featureBlueprint->getSlug();

        if (empty($slug)) {
            $slug = $featureBlueprint->getType()->getSlug($featureBlueprint->getValue());
        } else {
            $slug = Str::slug($slug, '_', dictionary: [',' => '_', '.' => '_']);
        }

        $featureBlueprint->setSlug($slug);
    }
}
