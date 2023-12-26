<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Contracts\Filters;

use Illuminate\Database\Eloquent\Builder;

interface SaleProductQueryBuilderFilterContract
{
    public function apply(Builder $query, $value): Builder;
}
