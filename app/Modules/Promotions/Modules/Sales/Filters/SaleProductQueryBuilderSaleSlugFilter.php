<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Filters;

use App\Modules\Promotions\Modules\Sales\Contracts\Filters\SaleProductQueryBuilderFilterContract;
use Illuminate\Database\Eloquent\Builder;

class SaleProductQueryBuilderSaleSlugFilter implements SaleProductQueryBuilderFilterContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        $slugs = explode(',', $value);
        $this->filter($query, $slugs);

        return $query;
    }

    private function filter(Builder $query, array $slugs): void
    {
        $query->whereHas(
            'sale',
            fn (Builder $promotionBuilder) => $promotionBuilder
                ->whereIn('slug', $slugs)
        );
    }
}
