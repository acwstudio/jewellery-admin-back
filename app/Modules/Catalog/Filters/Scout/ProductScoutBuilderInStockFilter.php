<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Scout;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;
use OpenSearch\ScoutDriverPlus\Support\Query;

class ProductScoutBuilderInStockFilter implements ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void
    {
        $inStock = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (!is_bool($inStock)) {
            throw new \Exception('The in_stock parameter must be boolean.');
        }

        if ($inStock) {
            $this->filterInStock($builder);
        }
    }

    private function filterInStock(BoolQueryBuilder $builder): void
    {
        $queryGroup = Query::range();
        $queryGroup->field('product_offer_items.stock')->gt(0);

        $builder->filter($queryGroup);
    }
}
