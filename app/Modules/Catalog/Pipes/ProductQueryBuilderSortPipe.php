<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Pipes;

use App\Modules\Catalog\Traits\CustomLeftJoinSubTrait;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Catalog\ProductSortColumnEnum;
use App\Packages\Enums\SortOrderEnum;
use Closure;
use App\Modules\Catalog\Contracts\Pipes\ProductQueryBuilderPipeContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class ProductQueryBuilderSortPipe implements ProductQueryBuilderPipeContract
{
    use CustomLeftJoinSubTrait;

    public function handle(Builder $query, Closure $next): Builder
    {
        if ($this->getSortColumn() === ProductSortColumnEnum::POPULARITY) {
            $this->orderByPopularity($query);
        }

        if ($this->getSortColumn() === ProductSortColumnEnum::PRICE) {
            $this->orderByPrice($query);
        }

        if ($this->getSortColumn() === ProductSortColumnEnum::DISCOUNT) {
            $this->orderByDiscount($query);
        }

        if ($this->getSortColumn() === ProductSortColumnEnum::CREATED_AT) {
            $this->orderByCreatedAt($query);
        }

        $query->orderBy('id');

        return $next($query);
    }

    private function getSortColumn(): ProductSortColumnEnum
    {
        $default = ProductSortColumnEnum::POPULARITY;

        if (!request()->has('sort_by')) {
            return $default;
        }

        return ProductSortColumnEnum::tryFrom(request('sort_by')) ?? $default;
    }

    private function getSortOrder(): SortOrderEnum
    {
        $default = SortOrderEnum::DESC;

        if (!request()->has('sort_order')) {
            return $default;
        }

        return SortOrderEnum::tryFrom(request('sort_order')) ?? $default;
    }

    private function orderByPopularity(Builder $query): void
    {
        $query->orderByRaw('popularity ' . $this->getSortOrder()->value . ' NULLS LAST');
    }

    private function orderByPrice(Builder $query): void
    {
        $this->getOrderByMinPrice($query);
    }

    private function orderByDiscount(Builder $query): void
    {
        $this->getOrderByDiscount($query);
        $query->orderByRaw('popularity ' . SortOrderEnum::DESC->value . ' NULLS LAST');
    }

    private function orderByCreatedAt(Builder $query): void
    {
        $query->orderBy('created_at', $this->getSortOrder()->value);
    }

    private function getOrderByMinPrice(Builder $query): void
    {
        $as = $this->getAsMinPrice($query->getQuery());
        $query->orderByRaw("{$as}.price {$this->getSortOrder()->value} NULLS LAST");
    }

    private function getOrderByDiscount(Builder $query): void
    {
        $regularPrices = DB::table('catalog.product_offer_prices')
            ->select('product_offer_id', 'price')
            ->where('is_active', '=', true)
            ->where('type', '=', OfferPriceTypeEnum::REGULAR);

        $promoPrices = DB::table('catalog.product_offer_prices', 'sort_pop')
            ->select([
                'sort_pop.product_offer_id as product_offer_id',
                'sort_pop.price as price',
                DB::raw("ROW_NUMBER() OVER (PARTITION BY sort_pop.product_offer_id ORDER BY
                  CASE
                    WHEN sort_pop.type = 'promo' THEN 2
                    WHEN sort_pop.type = 'sale' THEN 1
                  END
                ) AS row_num")
            ])
            ->where('sort_pop.is_active', '=', true)
            ->where('sort_pop.price', '>', 0)
            ->whereNot('sort_pop.type', '=', OfferPriceTypeEnum::EMPLOYEE);

        $livePrices = DB::table('catalog.product_offer_prices')
            ->select('product_offer_id', 'price')
            ->where('is_active', '=', true)
            ->where('type', '=', OfferPriceTypeEnum::LIVE);

        $discountOffers = DB::table('catalog.product_offers')
            ->joinSub(
                $regularPrices,
                'regular_prices',
                fn (JoinClause $join) => $join
                    ->on('product_offers.id', '=', 'regular_prices.product_offer_id')
            )
            ->joinSub(
                $promoPrices,
                'promo_prices',
                fn (JoinClause $join) => $join
                    ->on('product_offers.id', '=', 'promo_prices.product_offer_id')
            )
            ->leftJoinSub(
                $livePrices,
                'live_prices',
                fn (JoinClause $join) => $join
                    ->on('product_offers.id', '=', 'live_prices.product_offer_id')
            )
            ->select(
                'product_id',
                DB::raw("NULLIF(MAX(((regular_prices.price::numeric - promo_prices.price::numeric)/
                regular_prices.price::numeric * 100)), 0) as discount")
            )
            ->where('promo_prices.row_num', '=', '1')
            ->whereNull('live_prices.product_offer_id')
            ->groupBy('product_id');

        $query
            ->leftJoinSub(
                $discountOffers,
                'discount_offers',
                fn (JoinClause $join) => $join
                    ->on('products.id', '=', 'discount_offers.product_id')
            )
            ->orderByRaw('discount_offers.discount ' . $this->getSortOrder()->value . ' NULLS LAST');
    }
}
