<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductImageUrl;
use App\Modules\Catalog\Support\Blueprints\ProductImageUrlBlueprint;
use App\Modules\Catalog\Support\Filters\ProductImageUrlFilter;
use App\Packages\Support\FilterQuery\FilterQueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductImageUrlRepository
{
    public function getById(int $id, bool $fail = false): ?ProductImageUrl
    {
        if ($fail) {
            return ProductImageUrl::findOrFail($id);
        }

        return ProductImageUrl::find($id);
    }

    /**
     * @param ProductImageUrlFilter $filter
     * @param bool $fail
     * @return Collection<ProductImageUrl>
     */
    public function getCollectionByFilter(ProductImageUrlFilter $filter, bool $fail = false): Collection
    {
        $query = FilterQueryBuilder::fromQuery(ProductImageUrl::query())->withFilter($filter)->create();

        /** @var Collection<ProductImageUrl> $models */
        $models = $query->get();

        if ($fail && $models->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $models;
    }

    public function create(
        ProductImageUrlBlueprint $blueprintData,
        Product $product
    ): ProductImageUrl {
        $model = new ProductImageUrl([
            'path' => $blueprintData->path,
            'is_main' => $blueprintData->is_main
        ]);

        if ($blueprintData->is_main) {
            $product->imageUrls()->getQuery()->update(['is_main' => false]);
        }

        $model->product()->associate($product);
        $model->save();

        return $model;
    }

    public function update(
        ProductImageUrl $productImageUrl,
        ProductImageUrlBlueprint $blueprintData
    ): void {
        $productImageUrl->update([
            'path' => $blueprintData->path,
            'is_main' => $blueprintData->is_main
        ]);
    }

    public function delete(ProductImageUrl $productImageUrl): void
    {
        $productImageUrl->delete();
    }
}
