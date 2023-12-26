<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderOfferPriceTypeFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        $types = explode(',', $value);
        $this->priceTypes($query, $types);

        return $query;
    }

    private function priceTypes(Builder $query, array $types): void
    {
        $query->whereHas(
            'productOffers.productOfferPrices',
            fn (Builder $productOfferPriceBuilder) => $productOfferPriceBuilder
                ->where('is_active', '=', true)
                ->whereIn('type', $types)
        );
    }
}
