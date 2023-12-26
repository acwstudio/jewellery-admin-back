<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderBrandFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (is_array($value)) {
            return $this->filterByIds($query, $value);
        }

        return $this->filterById($query, $value);
    }

    private function filterById(Builder $query, int $id): Builder
    {
        return $query->where('brand_id', '=', $id);
    }

    private function filterByIds(Builder $query, array $ids): Builder
    {
        return $query->whereIn('brand_id', $ids);
    }
}
