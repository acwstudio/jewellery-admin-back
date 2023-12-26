<?php

declare(strict_types=1);

namespace App\Modules\Orders\Pipes;

use App\Modules\Orders\Contracts\Pipes\OrderQueryBuilderPipeContract;
use App\Packages\Enums\Catalog\ProductSortColumnEnum;
use App\Packages\Enums\Orders\OrderSortColumnEnum;
use App\Packages\Enums\SortOrderEnum;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class OrderQueryBuilderSortPipe implements OrderQueryBuilderPipeContract
{
    public function handle(Builder $query, Closure $next): Builder
    {
        $column = $this->getSortColumn();
        $sort = $this->getSortOrder();

        match ($column) {
            OrderSortColumnEnum::CREATED_AT => $this->orderByCreatedAt($query, $sort),
            OrderSortColumnEnum::SUMMARY => $this->orderBySummary($query, $sort)
        };

        return $next($query);
    }

    private function getSortColumn(): OrderSortColumnEnum
    {
        $default = OrderSortColumnEnum::CREATED_AT;

        if (!request()->has('sort_by')) {
            return $default;
        }

        return OrderSortColumnEnum::tryFrom(request('sort_by')) ?? $default;
    }

    private function getSortOrder(): SortOrderEnum
    {
        $default = SortOrderEnum::DESC;

        if (!request()->has('sort_order')) {
            return $default;
        }

        return SortOrderEnum::tryFrom(request('sort_order')) ?? $default;
    }

    private function orderBySummary(Builder $query, SortOrderEnum $sort): void
    {
        $query->orderBy('summary', $sort->value);
    }

    private function orderByCreatedAt(Builder $query, SortOrderEnum $sort): void
    {
        $query->orderBy('created_at', $sort->value);
    }
}
