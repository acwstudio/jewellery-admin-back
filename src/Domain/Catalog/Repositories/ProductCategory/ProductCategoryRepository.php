<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\ProductCategory;

use Domain\Catalog\Models\ProductCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{

    public function index(array $data): Paginator
    {
        return QueryBuilder::for(ProductCategory::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('product_categories'))
            ->allowedIncludes(['products'])
            ->allowedFilters([
                AllowedFilter::exact('slug'),
                AllowedFilter::exact('id'),
                'name','active'
            ])
            ->allowedSorts(['name','id','slug'])
            ->paginate($data['per_page'] ?? null)
            ->appends($data);
    }

    public function store(array $data): Model|ProductCategory
    {
        return ProductCategory::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|ProductCategory
    {
        return QueryBuilder::for(ProductCategory::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('product_categories'))
            ->allowedIncludes(['products'])
            ->firstOrFail();
    }

    public function update(array $data): Model|ProductCategory
    {
        $model = ProductCategory::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        ProductCategory::findOrFail($id)->delete();
    }
}
