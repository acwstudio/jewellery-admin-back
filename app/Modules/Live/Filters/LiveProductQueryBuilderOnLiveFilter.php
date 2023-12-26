<?php

declare(strict_types=1);

namespace App\Modules\Live\Filters;

use App\Modules\Live\Contracts\Filters\LiveProductQueryBuilderFilterContract;
use Illuminate\Database\Eloquent\Builder;

class LiveProductQueryBuilderOnLiveFilter implements LiveProductQueryBuilderFilterContract
{
    public function apply(Builder $query, $value): Builder
    {
        $onLive = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (!is_bool($onLive)) {
            throw new \Exception('The on_live parameter must be boolean.');
        }

        $query->where('on_live', '=', $onLive);

        return $query;
    }
}
