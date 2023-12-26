<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Feature;

use App\Modules\Catalog\Contracts\Filters\FilterFeatureQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class FeatureQueryBuilderTypeFilter implements FilterFeatureQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        $query->where('type', '=', $value);
        return $query;
    }
}
