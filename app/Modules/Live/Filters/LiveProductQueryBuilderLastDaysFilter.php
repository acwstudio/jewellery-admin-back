<?php

declare(strict_types=1);

namespace App\Modules\Live\Filters;

use App\Modules\Live\Contracts\Filters\LiveProductQueryBuilderFilterContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class LiveProductQueryBuilderLastDaysFilter implements LiveProductQueryBuilderFilterContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        $dateLast = Carbon::now()->subDays(intval($value));

        $query->where('started_at', '>=', $dateLast);

        return $query;
    }
}
