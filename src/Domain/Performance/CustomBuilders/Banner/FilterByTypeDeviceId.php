<?php

declare(strict_types=1);

namespace Domain\Performance\CustomBuilders\Banner;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

final class FilterByTypeDeviceId implements Filter
{
    public function __invoke(Builder $query, $value, string $property): Builder
    {
//        return $query->whereHas('imageBanners', function (Builder $query) use ($value) {
//            $query->where('type_device_id', $value);
//        });
        return $query->with(['imageBanners' => function ($q) use ($value) {
            $q->where('type_device_id', $value)->orderBy('sequence');
        }]);
    }
}
