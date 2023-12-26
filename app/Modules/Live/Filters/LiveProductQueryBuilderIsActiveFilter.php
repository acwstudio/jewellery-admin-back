<?php

declare(strict_types=1);

namespace App\Modules\Live\Filters;

use App\Modules\Live\Contracts\Filters\LiveProductQueryBuilderFilterContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class LiveProductQueryBuilderIsActiveFilter implements LiveProductQueryBuilderFilterContract
{
    public function apply(Builder $query, $value): Builder
    {
        $isActive = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (!is_bool($isActive)) {
            throw new \Exception('The is_active parameter must be boolean.');
        }

        $dateNow = Carbon::now();

        if ($isActive) {
            $this->active($query, $dateNow);
        } else {
            $this->notActive($query, $dateNow);
        }

        return $query;
    }

    private function active(Builder $query, Carbon $date): void
    {
        $query->where(function (Builder $query) use ($date) {
            $query
                ->where('started_at', '<=', $date)
                ->where('expired_at', '>=', $date);
        });
    }

    private function notActive(Builder $query, Carbon $date): void
    {
        $query->where(function (Builder $query) use ($date) {
            $query
                ->where('started_at', '>', $date)
                ->orWhere('expired_at', '<', $date);
        });
    }
}
