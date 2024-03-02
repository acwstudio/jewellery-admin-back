<?php

declare(strict_types=1);

namespace Domain\Performance\Repositories\ImageBanner;

use Domain\Performance\CustomBuilders\ImageBanner\FilterByBannerId;
use Domain\Performance\Models\ImageBanner;
use Domain\Performance\Models\TypeDevice;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class ImageBannerRepository implements ImageBannerRepositoryInterface
{
    public function index(array $data): Paginator
    {
        return QueryBuilder::for(ImageBanner::class)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('image_banners'))
            ->allowedIncludes(['banners','typeDevice'])
            ->allowedFilters([
                AllowedFilter::exact('slug'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('name'),
                AllowedFilter::exact('type_device_id'),
                AllowedFilter::custom('banner_id', new FilterByBannerId()),
                'is_active'
            ])
            ->allowedSorts(['name','id','slug','type_device_id'])
            ->paginate($data['per_page'] ?? null)
            ->appends($data);
    }

    public function store(array $data): Model|ImageBanner
    {
        return ImageBanner::create(data_get($data, 'data.attributes'));
    }

    public function show(int $id, array $data): Model|ImageBanner
    {
        return QueryBuilder::for(ImageBanner::class)
            ->where('id', $id)
            ->allowedFields(\DB::getSchemaBuilder()->getColumnListing('image_banners'))
            ->allowedIncludes(['banners','typeDevice'])
            ->firstOrFail();
    }

    public function update(array $data): Model|ImageBanner
    {
        $model = ImageBanner::findOrFail(data_get($data, 'id'));
        $model->update(data_get($data, 'data.attributes'));

        return $model;
    }

    public function destroy(int $id): void
    {
        $imageLink = ImageBanner::findOrFail($id)->image_link;

        if (!is_null($imageLink)) {
            if (Storage::has($imageLink)) {
                Storage::delete($imageLink);
            }
        }
        ImageBanner::findOrFail($id)->delete();
    }
}
