<?php

declare(strict_types=1);

namespace Domain\Performance\Repositories\TypeBanner;

use Domain\Performance\Models\TypeBanner;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class TypeBannerRepository implements TypeBannerRepositoryInterface
{
    public function index(array $data): Paginator
    {
        return QueryBuilder::for(TypeBanner::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('type_banners'))
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

    public function store(array $data): Model|TypeBanner
    {
        if (is_null(data_get($data, 'data.attributes.slug'))) {
            $data['data']['attributes']['slug'] = \Str::slug(data_get($data, 'data.attributes.type'));
        }
        return TypeBanner::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|TypeBanner
    {
        return QueryBuilder::for(TypeBanner::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('type_banners'))
            ->allowedIncludes(['banners'])
            ->firstOrFail();
    }

    public function update(array $data): Model|TypeBanner
    {
        $model = TypeBanner::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        TypeBanner::findOrFail($id)->delete();
    }
}
