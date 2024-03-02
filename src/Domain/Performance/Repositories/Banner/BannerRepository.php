<?php

declare(strict_types=1);

namespace Domain\Performance\Repositories\Banner;

use Domain\Performance\CustomBuilders\Banner\FilterByTypeDeviceId;
use Domain\Performance\CustomBuilders\Banner\SortBySequence;
use Domain\Performance\Models\Banner;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

final class BannerRepository implements BannerRepositoryInterface
{
    public function index(array $data): Paginator
    {
        return QueryBuilder::for(Banner::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('banners'))
            ->allowedIncludes(['imageBanners','typeBanner','typePage'])
            ->allowedSorts([
                'name','id','slug','type_banner_id','type_page_id',
                AllowedSort::custom('sequence', new SortBySequence()),
            ])
            ->allowedFilters([
                AllowedFilter::exact('slug'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('name'),
                AllowedFilter::exact('type_banner_id'),
                AllowedFilter::exact('link'),
                AllowedFilter::exact('type_page_id'),
                AllowedFilter::custom('type_device_id', new FilterByTypeDeviceId()),
                'is_active',
            ])
            ->allowedSorts([
                'name','id','slug','type_banner_id','type_page_id'
            ])
            ->paginate($data['per_page'] ?? null)
            ->appends($data);
    }

    public function store(array $data): Model|Banner
    {
        if (is_null(data_get($data, 'data.attributes.slug'))) {
            $data['data']['attributes']['slug'] = \Str::slug(data_get($data, 'data.attributes.type'));
        }
        return Banner::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|Banner
    {
        return QueryBuilder::for(Banner::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('banners'))
            ->allowedIncludes(['imageBanners','typeBanner','typePage'])
            ->allowedFilters(AllowedFilter::custom('type_device_id', new FilterByTypeDeviceId()))
            ->firstOrFail();
    }

    public function update(array $data): Model|Banner
    {
        $model = Banner::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        Banner::findOrFail($id)->delete();
    }
}
