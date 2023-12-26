<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Filters;

use App\Modules\Promotions\Modules\Sales\Contracts\Filters\SaleProductQueryBuilderFilterContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SaleProductQueryBuilderSaleIsActiveFilter implements SaleProductQueryBuilderFilterContract
{
    public function apply(Builder $query, $value): Builder
    {
        $isActive = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

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
        $query->whereHas(
            'sale.promotion',
            fn (Builder $promotionBuilder) => $promotionBuilder
                ->whereHas(
                    'condition',
                    fn (Builder $conditionBuilder) => $conditionBuilder
                        ->where('start_at', '<=', $date)
                        ->where('finish_at', '>=', $date)
                )
                ->where('is_active', '=', true)
        );
    }

    private function notActive(Builder $query, Carbon $date): void
    {
        $query->whereHas(
            'sale.promotion',
            fn (Builder $promotionBuilder) => $promotionBuilder
                ->whereHas(
                    'condition',
                    fn (Builder $conditionBuilder) => $conditionBuilder
                        ->where('start_at', '>=', $date)
                        ->orWhere('finish_at', '<=', $date)
                )
                ->orWhere('is_active', '=', false)
        );
    }
}
