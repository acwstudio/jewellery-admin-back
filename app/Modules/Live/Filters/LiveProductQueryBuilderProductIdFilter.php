<?php

declare(strict_types=1);

namespace App\Modules\Live\Filters;

use App\Modules\Live\Contracts\Filters\LiveProductQueryBuilderFilterContract;
use Illuminate\Database\Eloquent\Builder;

class LiveProductQueryBuilderProductIdFilter implements LiveProductQueryBuilderFilterContract
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
        $query->where('product_id', '=', $id);
    }

    private function filterByIds(Builder $query, array $ids): void
    {
        $query->whereIn('product_id', $ids);
    }
}
