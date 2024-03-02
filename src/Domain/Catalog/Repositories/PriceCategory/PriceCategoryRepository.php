<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\PriceCategory;

use Domain\Catalog\Models\PriceCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class PriceCategoryRepository implements PriceCategoryRepositoryInterface
{

    public function index(array $data): Paginator
    {
        return QueryBuilder::for(PriceCategory::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('price_categories'))
            ->allowedIncludes(['prices','products'])
            ->allowedFilters([
                'slug','is_active','name'
            ])
            ->allowedSorts(['name','id','slug'])
            ->paginate($data['per_page'] ?? null)
            ->appends($data);
    }

    public function store(array $data): Model|PriceCategory
    {
        return PriceCategory::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|PriceCategory
    {
        return QueryBuilder::for(PriceCategory::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('price_categories'))
            ->allowedIncludes(['prices','products'])
            ->firstOrFail();
    }

    public function update(array $data): Model|PriceCategory
    {
        $model = PriceCategory::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        PriceCategory::findOrFail($id)->delete();
    }
}
