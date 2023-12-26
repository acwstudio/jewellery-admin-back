<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Contracts\Filters;

use Illuminate\Database\Eloquent\Builder;

interface FilterFeatureQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder;
}
