<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\ProductCategory;

use Domain\AbstractRelationsRepository;
use Domain\Catalog\Models\ProductCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class ProductCategoryRelationsRepository extends AbstractRelationsRepository
{

    public function indexRelations(array $data): Paginator|Model
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        if (in_array(ProductCategory::findOrFail($id)->{$relation}()::class, config('api-settings.to-one')))
        {
            return ProductCategory::findOrFail($id)->{$relation}()->firstOrFail();
        }

        return ProductCategory::findOrFail($id)->{$relation}()->simplePaginate($perPage)->appends(data_get($data, 'params'));
    }

    /**
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        /**
         * HasOne, HasMany, MorphOne, MorphMany
         * BelongsTo, MorphTo
         * BelongsToMany, MorphedToMany, MorphedByMany
         */

            data_get($data, 'model') ?? data_set($data, 'model', ProductCategory::findOrFail(data_get($data, 'id')));

        $this->handleUpdateRelations($data);
    }
}
