<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Product;

use Domain\Catalog\CustomBuilders\Product\FilterRelatedSizesByBalance;
use Domain\Catalog\CustomBuilders\Product\FilterRelatedSizesByIsActive;
use Domain\Catalog\Models\Product;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ProductRepository implements ProductRepositoryInterface
{
    public function index(array $data): Paginator
    {
        return QueryBuilder::for(Product::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('products'))
            ->allowedIncludes(['weaves','blogPosts','sizeCategories','brand','sizes','productCategory','prices'])
            ->allowedFilters([
                AllowedFilter::exact('slug'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('sku'),
                AllowedFilter::custom('activeSizes', new FilterRelatedSizesByIsActive()),
                AllowedFilter::custom('balance', new FilterRelatedSizesByBalance()),
//                AllowedFilter::exact('sizes.balance'),
                AllowedFilter::exact('product_category_id'),
                AllowedFilter::exact('is_active'),
                'name'
            ])
            ->allowedSorts(['name','id','slug','product_category_id','sku'])
//            ->addSelect(data_get($data, 'fields') ? 'id' : '*', DB::raw(
//                '(SELECT name FROM product_categories where product_category_id = id) as product_category_name'))
//            ->addSelect(data_get($data, 'fields') ? 'id' : '*', DB::raw('(SELECT name FROM brands where brand_id = id)
//                as brand_name'))
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
            ->allowedIncludes(['weaves','blogPosts','sizeCategories','brand','sizes','productCategory','prices'])
            ->allowedFilters([
                AllowedFilter::custom('activeSizes', new FilterRelatedSizesByIsActive()),
                AllowedFilter::custom('balance', new FilterRelatedSizesByBalance()),
            ])
            ->addSelect(data_get($data, 'fields') ? 'id' : '*', DB::raw('(SELECT name FROM product_categories where product_category_id = id)
                as product_category_name'))
            ->addSelect(data_get($data, 'fields') ? 'id' : '*', DB::raw('(SELECT name FROM brands where brand_id = id)
                as brand_name'))
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
