<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderSkuFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        $values = explode(',', $value);
        $this->filter($query, $values);

        return $query;
    }

    private function filter(Builder $query, array $values): void
    {
        $query->whereIn('sku', $values);
    }
}
