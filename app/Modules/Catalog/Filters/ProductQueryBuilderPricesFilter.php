<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use App\Modules\Catalog\Traits\CustomLeftJoinSubTrait;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderPricesFilter implements FilterProductQueryBuilderContract
{
    use CustomLeftJoinSubTrait;

    public function apply(Builder $query, $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        $values = explode(',', $value);

        $prices = [];
        foreach ($values as $price) {
            $priceMinMax = explode('-', $price);
            $min = $this->convertValue($priceMinMax[0] ?? 0, true);
            $max = $this->convertValue($priceMinMax[1] ?? 0, true);

            if ($min > $max) {
                continue;
            }

            $prices[] = [$min, $max];
        }

        $this->filterPrices($query, $prices);

        return $query;
    }

    private function filterPrices(Builder $query, array $prices): void
    {
        if (empty($prices)) {
            return;
        }

        $as = $this->getAsMinPrice($query->getQuery());
        $query->where(function (Builder $query) use ($as, $prices) {
            foreach ($prices as $price) {
                $query
                    ->orWhereRaw("{$as}.price >= {$price[0]} AND {$as}.price <= {$price[1]}");
            }
        });
    }

    private function convertValue($value, bool $isDecimal = false): int
    {
        $value = intval($value);

        if ($isDecimal) {
            $value = $value * 100;
        }

        return $value;
    }
}
