<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderExcludeSkuFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        $this->filter($query, $value);

        return $query;
    }

    private function filter(Builder $query, string $value): void
    {
        $query->whereNot('sku', 'ilike', "%$value%");
    }
}
