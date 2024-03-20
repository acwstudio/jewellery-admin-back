<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Price;

use Domain\AbstractRelationsRepository;
use Domain\Catalog\Models\Price;
use Illuminate\Support\Facades\DB;

final class PriceRelationsRepository extends AbstractRelationsRepository
{
    public function indexPricesProduct(array $data)
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');

        return Price::findOrFail($id)->$relation
            ->addSelect('*', DB::raw('(select name from product_categories as pc
            where pc.id = products.product_category_id) as product_category_name'))
             ->firstOrFail();
    }

    public function indexPricesSize(array $data)
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');

        return Price::findOrFail($id)->$relation()
            ->addSelect('*', DB::raw('(select name from size_categories as sc
            where sc.id = sizes.size_category_id) as size_category_name'))
            ->addSelect('*', DB::raw('(select name from products as p
            where p.id = sizes.product_id) as product_name'))
            ->firstOrFail();
    }

    public function indexPricesPriceCategory(array $data)
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');

        return Price::findOrFail($id)->$relation()->firstOrFail();
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

            data_get($data, 'model') ?? data_set($data, 'model', Price::findOrFail(data_get($data, 'id')));

        $this->handleUpdateRelations($data);
    }
}
