<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Scout;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;
use OpenSearch\ScoutDriverPlus\Support\Query;

class ProductScoutBuilderPriceFilter implements ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void
    {
        $min = $this->convertValue($value['min'], true);
        $max = $this->convertValue($value['max'], true);

        if ($min > $max) {
            throw new \Exception('The MIN parameter cannot be greater than the MAX parameter.');
        }

        $this->priceByMin($builder, $min, $max);
    }

    private function priceByMin(BoolQueryBuilder $builder, int $min, int $max): void
    {
        $queryGroup = Query::bool();
        $queryGroup->filter(
            Query::range()->field('price_min')->gte($min)
        );
        $queryGroup->filter(
            Query::range()->field('price_min')->lte($max)
        );

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
