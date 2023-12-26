<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductVideoUrl;
use App\Modules\Catalog\Support\Filters\ProductVideoUrlFilter;
use App\Packages\Support\FilterQuery\FilterQueryBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ProductVideoUrlRepository
{
    public function getById(int $id, bool $fail = false): ?ProductVideoUrl
    {
        if ($fail) {
            return ProductVideoUrl::findOrFail($id);
        }

        return ProductVideoUrl::find($id);
    }

    /**
     * @return Collection<ProductVideoUrl>
     */
    public function getCollectionByFilter(ProductVideoUrlFilter $filter, bool $fail = false): Collection
    {
        $query = FilterQueryBuilder::fromQuery(ProductVideoUrl::query())->withFilter($filter)->create();

        /** @var Collection<ProductVideoUrl> $models */
        $models = $query->get();

        if ($fail && $models->count() === 0) {
            throw (new ModelNotFoundException())->setModel(ProductVideoUrl::class);
        }

        return $models;
    }

    public function create(string $path, Product $product): ProductVideoUrl
    {
        $model = new ProductVideoUrl([
            'path' => $path
        ]);

        $model->product()->associate($product);
        $model->save();

        return $model;
    }

    public function update(ProductVideoUrl $model, string $path): void
    {
        $model->update([
            'path' => $path
        ]);
    }

    public function delete(ProductVideoUrl $model): void
    {
        $model->delete();
    }
}
