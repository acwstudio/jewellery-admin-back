<?php

declare(strict_types=1);

namespace App\Modules\Orders\Contracts\Filters;

use Illuminate\Database\Eloquent\Builder;

interface OrderQueryBuilderFilterContract
{
    public function apply(Builder $query, $value): Builder;
}
