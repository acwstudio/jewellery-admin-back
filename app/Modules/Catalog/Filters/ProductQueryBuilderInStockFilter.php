<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderInStockFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        $inStock = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (!is_bool($inStock)) {
            throw new \Exception('The in_stock parameter must be boolean.');
        }

        if ($inStock) {
            $this->filterInStock($query);
        } else {
            $this->filterNotInStock($query);
        }

        return $query;
    }

    private function filterInStock(Builder $query): void
    {
        $query
            ->whereHas(
                'productOffers',
                fn (Builder $productOfferBuilder) => $productOfferBuilder->whereHas(
                    'productOfferStocks',
                    fn (Builder $productOfferStockBuilder) => $productOfferStockBuilder
                        ->where('is_current', '=', true)
                        ->where('count', '>', 0)
                )
            );
    }

    private function filterNotInStock(Builder $query): void
    {
        $query
            ->whereHas(
                'productOffers',
                fn (Builder $productOfferBuilder) => $productOfferBuilder
                    ->whereDoesntHave('productOfferStocks')
                    ->orWhereHas(
                        'productOfferStocks',
                        fn (Builder $productOfferStockBuilder) => $productOfferStockBuilder
                            ->where(
                                fn (Builder $builder) => $builder
                                    ->where('is_current', '=', true)
                                    ->where('count', '=', 0)
                            )
                    )
            );
    }
}
