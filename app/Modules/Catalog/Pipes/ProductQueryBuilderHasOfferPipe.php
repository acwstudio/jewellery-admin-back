<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Pipes;

use Closure;
use App\Modules\Catalog\Contracts\Pipes\ProductQueryBuilderPipeContract;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderHasOfferPipe implements ProductQueryBuilderPipeContract
{
    public function handle(Builder $query, Closure $next): Builder
    {
        /** TODO Добавить проверку на админа */
        $isAll = true;

        if ($isAll) {
            return $next($query);
        }

        $query->whereHas(
            'productOffers',
            fn (Builder $productOfferBuilder) => $productOfferBuilder->whereHas(
                'productOfferStocks'
            )
        );

        return $next($query);
    }
}
