<?php

declare(strict_types=1);

namespace App\Modules\Orders\Filters;

use App\Modules\Orders\Contracts\Filters\OrderQueryBuilderFilterContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class OrderQueryBuilderBetweenDatetimeFilter implements OrderQueryBuilderFilterContract
{
    public function apply(Builder $query, $value): Builder
    {
        $start = $this->convertCarbon($value['start']);
        $end = $this->convertCarbon($value['end']);

        $this->createdAt($query, $start, $end);

        return $query;
    }

    private function createdAt(Builder $query, Carbon $dateStart, Carbon $dateEnd): void
    {
        $query->where(function (Builder $query) use ($dateStart, $dateEnd) {
            $query
                ->where('created_at', '>=', $dateStart)
                ->where('created_at', '<=', $dateEnd);
        });
    }

    private function convertCarbon(string $datetime): Carbon
    {
        return Carbon::parse($datetime);
    }
}
