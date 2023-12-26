<?php

declare(strict_types=1);

namespace App\Modules\Collections\Filters;

use App\Modules\Collections\Contracts\Filters\FilterStoneQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class StoneQueryBuilderNameFilter implements FilterStoneQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        return $query->where('name', 'ILIKE', "%{$value}%");
    }
}
