<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Scout;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;
use OpenSearch\ScoutDriverPlus\Support\Query;

class ProductScoutBuilderSkuFilter implements ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void
    {
        if (empty($value)) {
            return;
        }

        $values = explode(',', $value);
        $this->filter($builder, $values);
    }

    private function filter(BoolQueryBuilder $builder, array $values): void
    {
        $builder->filter(
            Query::terms()->field('sku')->values($values)
        );
    }
}
