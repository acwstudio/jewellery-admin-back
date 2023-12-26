<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Scout;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use App\Packages\Support\OpenSearch\Builders\QueryStringBuilder;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;

class ProductScoutBuilderExcludeSkuFilter implements ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void
    {
        if (empty($value)) {
            return;
        }

        $this->filter($builder, $value);
    }

    private function filter(BoolQueryBuilder $builder, string $value): void
    {
        $queryGroup = new QueryStringBuilder();
        $queryGroup->query("sku: *{$value}*");

        $builder->mustNot($queryGroup);
    }
}
