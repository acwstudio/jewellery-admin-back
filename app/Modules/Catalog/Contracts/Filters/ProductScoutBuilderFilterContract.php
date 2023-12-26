<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Contracts\Filters;

use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;

interface ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void;
}
