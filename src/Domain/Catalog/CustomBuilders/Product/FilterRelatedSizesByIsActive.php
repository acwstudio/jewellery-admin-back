<?php

declare(strict_types=1);

namespace Domain\Catalog\CustomBuilders\Product;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

final class FilterRelatedSizesByIsActive implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        return $query->with(['sizes' => function ($q) use ($value) {
            $q->where('is_active', $value)->orderBy('id');
        }]);
    }
}
