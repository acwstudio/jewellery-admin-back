<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Traits;

use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

trait CustomLeftJoinSubTrait
{
    public function checkJoined(Builder $query, string $as): bool
    {
        $joins = $query->joins;
        if (empty($joins)) {
            return false;
        }

        /** @var JoinClause $join */
        foreach ($joins as $join) {
            /** @var \Illuminate\Database\Query\Expression $table */
            $table = $join->table;
            $joinAs = $this->getAs($table->getValue());
            if ($joinAs === $as) {
                return true;
            }
        }

        return false;
    }

    public function getAsMinPrice(Builder $query): string
    {
        $as = 'min_offers';

        if ($this->checkJoined($query, $as)) {
            return $as;
        }

        $typePrices = DB::table('catalog.product_offer_prices', 'pop')
            ->select([
                'pop.product_offer_id',
                'pop.price',
                DB::raw("ROW_NUMBER() OVER (PARTITION BY pop.product_offer_id ORDER BY
                  CASE
                    WHEN pop.type = 'regular' THEN 4
                    WHEN pop.type = 'promo' THEN 3
                    WHEN pop.type = 'sale' THEN 2
                    WHEN pop.type = 'live' THEN 1
                  END
                ) AS row_num")
            ])
            ->where('pop.is_active', '=', true)
            ->where('pop.price', '>', 0)
            ->whereNot('pop.type', '=', OfferPriceTypeEnum::EMPLOYEE);

        $minPrices = DB::table('catalog.product_offer_prices', 'pop1')
            ->joinSub(
                $typePrices,
                'type_prices',
                fn (JoinClause $join) => $join
                    ->on('pop1.product_offer_id', '=', 'type_prices.product_offer_id')
            )
            ->select([
                'pop1.product_offer_id',
                DB::raw('MIN(type_prices.price) as min_price')
            ])
            ->where('type_prices.row_num', '=', '1')
            ->groupBy('pop1.product_offer_id');

        $minOffers = DB::table('catalog.product_offers')
            ->joinSub(
                $minPrices,
                'min_prices',
                fn (JoinClause $join) => $join
                    ->on('product_offers.id', '=', 'min_prices.product_offer_id')
            )
            ->select('product_id', DB::raw('MIN(min_prices.min_price) as price'))
            ->groupBy('product_id');

        $query
            ->leftJoinSub(
                $minOffers,
                $as,
                fn (JoinClause $join) => $join
                    ->on('products.id', '=', 'min_offers.product_id')
            );

        return $as;
    }

    private function getAs(string $table): string
    {
        $as = substr($table, strrpos($table, 'as') + 2);
        $as = trim($as);
        return trim($as, '"');
    }
}
