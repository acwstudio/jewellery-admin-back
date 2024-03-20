<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Product;

use Domain\AbstractRelationsRepository;
use Domain\Catalog\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class ProductRelationsRepository extends AbstractRelationsRepository
{
    public function indexProductsWeaves(array $data): Paginator
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return Product::findOrFail($id)->$relation()->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexProductSizes(array $data): Paginator
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return Product::findOrFail($id)->$relation()
            ->addSelect('*', DB::raw('(SELECT name FROM size_categories where size_category_id = id) as size_category_name'))
            ->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexProductSizeCategories(array $data): Paginator
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return Product::findOrFail($id)->$relation()->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexProductsBlogPosts(array $data)
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return Product::findOrFail($id)->$relation()
            ->addSelect('*', DB::raw('(SELECT name FROM blog_categories where blog_category_id = id) as size_category_name'))
            ->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexProductPrices(array $data): Paginator
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return Product::findOrFail($id)->$relation()
            ->addSelect(DB::raw('(SELECT name FROM price_categories where price_category_id = id) as price_category_name'))
            ->addSelect(DB::raw('(SELECT value FROM sizes where size_id = id) as size_value'))
            ->addSelect(DB::raw('(SELECT name FROM size_categories where size_category_id = id) as size_name'))
            ->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexProductsProductCategory(array $data): Model
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');

        return Product::findOrFail($id)->{$relation}()
            ->addSelect('*', DB::raw('(SELECT name FROM product_categories as pc where product_categories.parent_id = pc.id) as parent_name'))
            ->firstOrFail();
    }

//    public function indexRelations(array $data): Paginator|Model
//    {
//        $relation = data_get($data, 'relation_method');
//        $id = data_get($data, 'id');
//        $perPage = data_get($data, 'params.per_page');
//
//        if (in_array(Product::findOrFail($id)->{$relation}()::class, config('api-settings.to-one')))
//        {
//            return Product::findOrFail($id)->{$relation}()->firstOrFail();
//        }
//
//        return Product::findOrFail($id)->{$relation}()
//            ->addSelect('*', DB::raw('(SELECT name FROM price_categories where price_category_id = id) as price_category_name'))
//            ->addSelect('*', DB::raw('(SELECT value FROM sizes where size_id = id) as size_value'))
//            ->addSelect('*', DB::raw('(SELECT type FROM size_categories where size_category_id = id) as size_type'))
//            ->paginate($perPage)->appends(data_get($data, 'params'));
//    }

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

            data_get($data, 'model') ?? data_set($data, 'model', Product::findOrFail(data_get($data, 'id')));

        $this->handleUpdateRelations($data);
    }
}
