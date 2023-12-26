<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderIdFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (is_array($value)) {
            $this->filterByIds($query, $value);
        } else {
            $this->filterById($query, $value);
        }

        return $query;
    }

    private function filterById(Builder $query, int $id): void
    {
        $query->where('id', '=', $id);
    }

    private function filterByIds(Builder $query, array $ids): void
    {
        $query->whereIn('id', $ids);
    }
}
