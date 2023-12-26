<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderSizeFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        $values = explode(',', $value);
        $this->filterIn($query, $values);

        return $query;
    }

    private function filterIn(Builder $query, array $values): void
    {
        $query->whereHas(
            'productOffers',
            fn (Builder $productOfferBuilder) => $productOfferBuilder
                ->whereIn('size', $values)
                ->whereHas(
                    'productOfferStocks',
                    fn (Builder $productOfferStockBuilder) => $productOfferStockBuilder
                        ->where('is_current', '=', true)
                        ->where('count', '>', 0)
                )
        );
    }
}
