<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\PriceCategory;

use Domain\AbstractRelationsRepository;
use Domain\Catalog\Models\PriceCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class PriceCategoryRelationsRepository extends AbstractRelationsRepository
{
    public function indexPriceCategorySizes(array $data)
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return PriceCategory::findOrFail($id)->{$relation}()
            ->addSelect(DB::raw('(SELECT name FROM products where products.id = sizes.product_id)
            as product_name'))
            ->addSelect(DB::raw('(SELECT name FROM size_categories where size_categories.id = sizes.size_category_id)
            as size_category_name'))
            ->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexPriceCategoryPrices(array $data)
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return PriceCategory::findOrFail($id)->{$relation}()
            ->addSelect("*", DB::raw("(SELECT value FROM sizes where sizes.id = prices.size_id)
            as size_value"))
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

            data_get($data, 'model') ?? data_set($data, 'model', PriceCategory::findOrFail(data_get($data, 'id')));

        $this->handleUpdateRelations($data);
    }
}
