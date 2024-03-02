<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Price;

use Domain\Catalog\Models\Price;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class PriceRepository implements PriceRepositoryInterface
{
    public function index(array $data): Paginator
    {
        return QueryBuilder::for(Price::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('prices'))
            ->allowedIncludes(['priceCategory','product'])
            ->allowedFilters([
                AllowedFilter::exact('product_id'),
                AllowedFilter::exact('price_category_id'),
                'value','is_active'
            ])
            ->allowedSorts(['name','id','slug','product_category_id','sku'])
            ->paginate($data['per_page'] ?? null)
            ->appends($data);
    }

    public function store(array $data): Model|Price
    {
        return Price::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|Price
    {
        return QueryBuilder::for(Price::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('prices'))
            ->allowedIncludes(['priceCategory','product'])
            ->firstOrFail();
    }

    public function update(array $data): Model|Price
    {
        $model = Price::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        Price::findOrFail($id)->delete();
    }
}
