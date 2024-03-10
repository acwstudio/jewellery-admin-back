<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\Size;

use Domain\Catalog\Models\Size;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class SizeRepository implements SizeCategoryRepositoryInterface
{
    public function index(array $data): Paginator
    {
        return QueryBuilder::for(Size::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('sizes'))
            ->allowedIncludes(['sizeCategory','product','prices'])
            ->allowedFilters([
                AllowedFilter::exact('size_category_id'),
                AllowedFilter::exact('product_id'),
                AllowedFilter::exact('value'),
                'is_active'
            ])
            ->allowedSorts(['size_category_id','id','product_id'])
            ->paginate($data['per_page'] ?? null)
            ->appends($data);
    }

    public function store(array $data): Model|Size
    {
        return Size::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|Size
    {
        return QueryBuilder::for(Size::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('sizes'))
            ->allowedIncludes(['sizeCategory','product','prices'])
            ->firstOrFail();
    }

    public function update(array $data): Model|Size
    {
        $model = Size::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        Size::findOrFail($id)->delete();
    }
}
