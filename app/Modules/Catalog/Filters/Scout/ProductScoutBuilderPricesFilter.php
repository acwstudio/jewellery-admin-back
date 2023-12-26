<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Scout;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;
use OpenSearch\ScoutDriverPlus\Support\Query;

class ProductScoutBuilderPricesFilter implements ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void
    {
        if (empty($value)) {
            return;
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

            $prices[] = [
                'min' => $min,
                'max' => $max
            ];
        }

        $this->filterPrices($builder, $prices);
    }

    private function filterPrices(BoolQueryBuilder $builder, array $prices): void
    {
        if (empty($prices)) {
            return;
        }

        $queryGroup = Query::bool();
        foreach ($prices as $price) {
            $queryGroup->should(
                Query::range()->field('price_min')->gte($price['min'])->lte($price['max'])
            );
        }
        $queryGroup->minimumShouldMatch(1);
        $builder->filter($queryGroup);
    }

    private function convertValue($value, bool $isDecimal = false): int
    {
        $value = (int)$value;

        if ($isDecimal) {
            $value = $value * 100;
        }

        return $value;
    }
}
