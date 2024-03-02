<?php

declare(strict_types=1);

namespace Domain\Performance\Repositories\TypePage;

use Domain\Performance\Models\TypePage;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class TypePageRepository implements TypePageRepositoryInterface
{
    public function index(array $data): Paginator
    {
        return QueryBuilder::for(TypePage::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('type_pages'))
            ->allowedIncludes(['banners'])
            ->allowedFilters([
                AllowedFilter::exact('slug'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('type'),
                'is_active'
            ])
            ->allowedSorts(['name','id','slug'])
            ->paginate($data['per_page'] ?? null)
            ->appends($data);
    }

    public function store(array $data): Model|TypePage
    {
        if (is_null(data_get($data, 'data.attributes.slug'))) {
            $data['data']['attributes']['slug'] = \Str::slug(data_get($data, 'data.attributes.type'));
        }
        return TypePage::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|TypePage
    {
        return QueryBuilder::for(TypePage::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('type_pages'))
            ->allowedIncludes(['banners'])
            ->firstOrFail();
    }

    public function update(array $data): Model|TypePage
    {
        $model = TypePage::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        TypePage::findOrFail($id)->delete();
    }
}
