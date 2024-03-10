<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Product;

use Domain\Catalog\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ProductRepository implements ProductRepositoryInterface
{

    public function index(array $data): Paginator
    {
        return QueryBuilder::for(Product::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('products'))
            ->allowedIncludes(['weaves','blogPosts','sizeCategories','brand','sizes'])
            ->allowedFilters([
                AllowedFilter::exact('slug'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('sku'),
                AllowedFilter::exact('product_category_id'),
                'name','is_active'
            ])
            ->allowedSorts(['name','id','slug','product_category_id','sku'])
            ->paginate($data['per_page'] ?? null)
            ->appends($data);
    }

    public function store(array $data): Model|Product
    {
        return Product::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|Product
    {
        return QueryBuilder::for(Product::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('products'))
            ->allowedIncludes(['weaves','blogPosts','sizeCategories','brand','sizes'])
            ->firstOrFail();
    }

    public function update(array $data): Model|Product
    {
        $model = Product::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        Product::findOrFail($id)->delete();
    }
}
