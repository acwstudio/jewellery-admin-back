<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\ProductCategory;

use Domain\AbstractRelationsRepository;
use Domain\Catalog\Models\ProductCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class ProductCategoryRelationsRepository extends AbstractRelationsRepository
{
    public function indexProductCategoryProducts(array $data): Paginator
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return ProductCategory::findOrFail($id)->$relation()
            ->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexProductCategoryChildren(array $data): Paginator
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return ProductCategory::findOrFail($id)->$relation()
            ->addSelect('*', DB::raw('(select name from product_categories as pc
            where pc.id = product_categories.parent_id) as parent_name'))
            ->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexProductCategoriesParent(array $data): Model
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');

        return ProductCategory::findOrFail($id)->$relation()
            ->addSelect('*', DB::raw('(select name from product_categories as pc
            where pc.id = product_categories.parent_id) as parent_name'))
            ->firstOrFail();
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
