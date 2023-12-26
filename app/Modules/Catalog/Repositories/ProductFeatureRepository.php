<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Support\Blueprints\ProductFeatureBlueprint;
use App\Modules\Catalog\Support\Filters\ProductFeatureFilter;
use App\Packages\Support\FilterQuery\FilterQueryBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ProductFeatureRepository
{
    public function getByUuid(string $uuid, bool $fail = false): ?ProductFeature
    {
        if ($fail) {
            return ProductFeature::findOrFail($uuid);
        }

        return ProductFeature::find($uuid);
    }

    public function getByUuidAndNullParentUuid(string $uuid, bool $fail = false): ?ProductFeature
    {
        /** @var ProductFeature|null $model */
        $model = ProductFeature::query()->whereNull('parent_uuid')->find($uuid);

        if ($fail && !$model instanceof ProductFeature) {
            throw new ModelNotFoundException('Свойство продукта в качестве родительского не найдено');
        }

        return $model;
    }

    /**
     * @param ProductFeatureFilter $filter
     * @param bool $fail
     * @return Collection<ProductFeature>
     */
    public function getCollectionByFilter(ProductFeatureFilter $filter, bool $fail = false): Collection
    {
        $query = FilterQueryBuilder::fromQuery(ProductFeature::query())->withFilter($filter)->create();

        /** @var Collection<ProductFeature> $models */
        $models = $query->get();

        if ($fail && $models->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $models;
    }

    public function create(
        ProductFeatureBlueprint $productFeatureBlueprint,
        Product $product,
        Feature $feature,
        ?ProductFeature $parentProductFeature = null
    ): ProductFeature {
        $productFeature = new ProductFeature([
            'value' => $productFeatureBlueprint->value,
            'is_main' => $productFeatureBlueprint->is_main ?? false
        ]);

        $productFeature->product()->associate($product);
        $productFeature->feature()->associate($feature);

        if ($parentProductFeature instanceof ProductFeature) {
            $productFeature->parent()->associate($parentProductFeature);
        }

        $productFeature->save();

        return $productFeature;
    }

    public function update(
        ProductFeature $productFeature,
        ProductFeatureBlueprint $productFeatureBlueprint,
        ProductFeature|null $parentProductFeature,
    ): void {
        $productFeature->parent()->associate($parentProductFeature);
        $productFeature->update([
            'value' => $productFeatureBlueprint->value,
            'is_main' => $productFeatureBlueprint->is_main ?? false
        ]);
    }

    public function delete(ProductFeature $productFeature): void
    {
        $productFeature->delete();
    }
}
