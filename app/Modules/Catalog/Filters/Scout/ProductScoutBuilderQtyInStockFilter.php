<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Scout;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;
use OpenSearch\ScoutDriverPlus\Support\Query;

class ProductScoutBuilderQtyInStockFilter implements ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void
    {
        if (empty($value)) {
            return;
        }

        $qty = (int)$value;
        $this->filterQtyStock($builder, $qty);
    }

    private function filterQtyStock(BoolQueryBuilder $builder, int $qty): void
    {
        $queryGroup = Query::range();
        $queryGroup->field('product_offer_items.stock')->gte($qty);

        $builder->filter($queryGroup);
    }
}
