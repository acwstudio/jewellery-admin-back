<?php

declare(strict_types=1);

namespace Domain\Catalog\Repositories\SizeCategory;

use Domain\Catalog\Models\SizeCategory;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class SizeCategoryRepository implements SizeCategoryRepositoryInterface
{
    public function index(array $data): Paginator
    {
        return QueryBuilder::for(SizeCategory::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('size_categories'))
            ->allowedIncludes(['sizes','products'])
            ->allowedFilters([
                AllowedFilter::exact('type'),
                AllowedFilter::exact('slug'),
                'is_active'
            ])
            ->allowedSorts(['id','slug','type'])
            ->paginate($data['per_page'] ?? null)
            ->appends($data);
    }

    public function store(array $data): Model|SizeCategory
    {
        return SizeCategory::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|SizeCategory
    {
        return QueryBuilder::for(SizeCategory::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('sizes'))
            ->allowedIncludes(['products','sizes'])
            ->firstOrFail();
    }

    public function update(array $data): Model|SizeCategory
    {
        $model = SizeCategory::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        SizeCategory::findOrFail($id)->delete();
    }
}
