<?php

declare(strict_types=1);

namespace App\Modules\Collections\Contracts\Filters;

use Illuminate\Database\Eloquent\Builder;

interface FilterStoneQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder;
}
