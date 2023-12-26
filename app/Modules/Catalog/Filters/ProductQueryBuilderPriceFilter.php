<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use App\Modules\Catalog\Traits\CustomLeftJoinSubTrait;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderPriceFilter implements FilterProductQueryBuilderContract
{
    use CustomLeftJoinSubTrait;

    public function apply(Builder $query, $value): Builder
    {
        $min = $this->convertValue($value['min'], true);
        $max = $this->convertValue($value['max'], true);

        if ($min > $max) {
            throw new \Exception('The MIN parameter cannot be greater than the MAX parameter.');
        }

        $this->priceByMin($query, $min, $max);

        return $query;
    }

    private function priceByMin(Builder $query, int $min, int $max): void
    {
        $as = $this->getAsMinPrice($query->getQuery());
        $query
            ->where("{$as}.price", '>=', $min)
            ->where("{$as}.price", '<=', $max);
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
