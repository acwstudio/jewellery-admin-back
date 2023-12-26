<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Contracts\Filters;

use Illuminate\Database\Eloquent\Builder;

interface FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder;
}
