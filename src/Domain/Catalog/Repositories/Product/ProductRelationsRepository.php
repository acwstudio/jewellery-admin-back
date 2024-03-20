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
            ->addSelect('*', DB::raw('(SELECT name FROM size_categories as sc
            where sizes.size_category_id = sc.id) as size_category_name'))
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
            ->addSelect(DB::raw('(SELECT name FROM blog_categories as bc
            where blog_posts.blog_category_id = bc.id) as blog_category_name'))
            ->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexProductPrices(array $data): Paginator
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');
        $perPage = data_get($data, 'params.per_page');

        return Product::findOrFail($id)->$relation()
            ->addSelect(DB::raw('(SELECT name FROM price_categories as pc
            where prices.price_category_id = pc.id) as price_category_name'))
            ->addSelect(DB::raw('(SELECT value FROM sizes as s
            where prices.size_id = s.id) as size_value'))
            ->addSelect(DB::raw('(SELECT name FROM size_categories as sc
            where sizes.size_category_id = sc.id) as size_category_name'))
            ->paginate($perPage)->appends(data_get($data, 'params'));
    }

    public function indexProductsProductCategory(array $data): Model
    {
        $relation = data_get($data, 'relation_method');
        $id = data_get($data, 'id');

        return Product::findOrFail($id)->$relation()
            ->addSelect('*', DB::raw('(SELECT name FROM product_categories as pc
            where product_categories.parent_id = pc.id) as parent_name'))
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

            data_get($data, 'model') ?? data_set($data, 'model', Product::findOrFail(data_get($data, 'id')));

        $this->handleUpdateRelations($data);
    }
}
