<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Size;

use Domain\AbstractRelationsRepository;
use Domain\Catalog\Models\Size;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class SizeRelationsRepository extends AbstractRelationsRepository
{
    public function indexSizesProduct(array $data): Model
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');

        return Size::findOrFail($id)->{$relation}()
            ->addSelect('*', DB::raw('(select name from product_categories as pc
            where pc.id = products.product_category_id) as product_category_name'))
            ->firstOrFail();
    }

    public function indexSizesCategorySize(array $data): Model
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');

        return Size::findOrFail($id)->{$relation}()->firstOrFail();
    }

    public function indexSizePrices(array $data): Paginator
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return Size::findOrFail($id)->{$relation}()
            ->addSelect('*', DB::raw('(select name from price_categories as pc
            where pc.id = prices.price_category_id) as price_category_name'))
            ->addSelect('*', DB::raw('(select value from sizes as s
            where s.id = prices.size_id) as size_value'))
            ->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexSizesPriceCategories(array $data): Paginator
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return Size::findOrFail($id)->{$relation}()
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

            data_get($data, 'model') ?? data_set($data, 'model', Size::findOrFail(data_get($data, 'id')));

        $this->handleUpdateRelations($data);
    }
}
