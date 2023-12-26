<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Filters;

use App\Modules\Promotions\Modules\Sales\Contracts\Filters\SaleProductQueryBuilderFilterContract;
use Illuminate\Database\Eloquent\Builder;

class SaleProductQueryBuilderProductIdFilter implements SaleProductQueryBuilderFilterContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        $ids = explode(',', $value);
        $this->filter($query, $ids);

        return $query;
    }

    private function filter(Builder $query, array $ids): void
    {
        $query->whereIn('product_id', $ids);
    }
}
