<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderSearchFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        $this->filter($query, (string)$value);

        return $query;
    }

    private function filter(Builder $query, string $value): void
    {
        $query->where(
            fn (Builder $builder) => $builder
                ->where('sku', 'ILIKE', "%{$value}%")
                ->orWhere('name', 'ILIKE', "%{$value}%")
        );
    }
}
