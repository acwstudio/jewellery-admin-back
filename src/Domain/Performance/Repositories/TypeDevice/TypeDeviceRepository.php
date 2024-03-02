<?php

declare(strict_types=1);

namespace Domain\Performance\Repositories\TypeDevice;

use Domain\Performance\Models\TypeDevice;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class TypeDeviceRepository implements TypeDeviceRepositoryInterface
{
    public function index(array $data): Paginator
    {
        return QueryBuilder::for(TypeDevice::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('type_devices'))
            ->allowedIncludes(['imageBanners'])
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

    public function store(array $data): Model|TypeDevice
    {
        if (is_null(data_get($data, 'data.attributes.slug'))) {
            $data['data']['attributes']['slug'] = \Str::slug(data_get($data, 'data.attributes.type'));
        }

        return TypeDevice::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|TypeDevice
    {
        return QueryBuilder::for(TypeDevice::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('type_devices'))
            ->allowedIncludes(['imageBanners'])
            ->firstOrFail();
    }

    public function update(array $data): Model|TypeDevice
    {
        if (is_null(data_get($data, 'data.attributes.slug'))) {
            $data['data']['attributes']['slug'] = \Str::slug(data_get($data, 'data.attributes.type'));
        }
        $model = TypeDevice::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        TypeDevice::findOrFail($id)->delete();
    }
}
