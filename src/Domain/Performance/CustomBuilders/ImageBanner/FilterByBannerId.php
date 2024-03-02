<?php

declare(strict_types=1);

namespace Domain\Performance\CustomBuilders\ImageBanner;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

final class FilterByBannerId implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        return $query->whereHas('banners', function (Builder $query) use ($value) {
            $query->where('banners.id', $value);
        });
    }
}
