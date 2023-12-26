<?php

declare(strict_types=1);

namespace App\Modules\Live\Contracts\Filters;

use Illuminate\Database\Eloquent\Builder;

interface LiveProductQueryBuilderFilterContract
{
    public function apply(Builder $query, $value): Builder;
}
