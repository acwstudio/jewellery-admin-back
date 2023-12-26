<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderQtyInStockFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        $qty = intval($value);
        $this->filterQtyStock($query, $qty);

        return $query;
    }

    private function filterQtyStock(Builder $query, int $qty): void
    {
        $query
            ->whereHas(
                'productOffers.productOfferStocks',
                fn (Builder $productOfferStockBuilder) => $productOfferStockBuilder
                    ->where('is_current', '=', true)
                    ->where('count', '>=', $qty)
            );
    }
}
