<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\SizeCategory;

use Domain\AbstractRelationsRepository;
use Domain\Catalog\Models\SizeCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class SizeCategoryRelationsRepository extends AbstractRelationsRepository
{
    public function indexSizeCategoryPrices(array $data): Paginator|Model
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return SizeCategory::findOrFail($id)->{$relation}()
            ->addSelect(DB::raw('(select name from price_categories as pc
            where pc.id = prices.price_category_id) as price_category_name'))
            ->addSelect(DB::raw('(select value from sizes as s
            where s.id = prices.size_id) as size_value'))
            ->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexSizeCategorySizes(array $data): Paginator|Model
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return SizeCategory::findOrFail($id)->{$relation}()
            ->addSelect('*', DB::raw('(select name from products as p
            where p.id = sizes.product_id) as product_name'))
            ->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexSizeCategoriesProducts(array $data): Paginator|Model
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return SizeCategory::findOrFail($id)->{$relation}()
            ->addSelect(DB::raw('(select name from product_categories as pc
            where pc.id = products.product_category_id) as product_category_name'))
            ->addSelect(DB::raw('(select name from brands as b
            where b.id = products.brand_id) as brand_name'))
            ->paginate($perPage)->appends(data_get($data, 'params'));
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

            data_get($data, 'model') ?? data_set($data, 'model', SizeCategory::findOrFail(data_get($data, 'id')));

        $this->handleUpdateRelations($data);
    }
}
