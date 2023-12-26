<?php

declare(strict_types=1);

namespace App\Modules\Live\Pipes;

use App\Modules\Live\Contracts\Pipes\LiveProductQueryBuilderPipeContract;
use App\Modules\Live\Enums\LiveProductSortColumnEnum;
use App\Packages\Enums\SortOrderEnum;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class LiveProductQueryBuilderSortPipe implements LiveProductQueryBuilderPipeContract
{
    public function handle(Builder $query, Closure $next): Builder
    {
        match ($this->getSortColumn()) {
            LiveProductSortColumnEnum::POPULAR => $this->popular($query),
            default => $this->default($query)
        };

        return $next($query);
    }

    private function getSortColumn(): LiveProductSortColumnEnum
    {
        $default = LiveProductSortColumnEnum::CREATED_AT;

        if (!request()->has('sort_by')) {
            return $default;
        }

        return LiveProductSortColumnEnum::tryFrom(request('sort_by')) ?? $default;
    }

    private function getSortOrder(): SortOrderEnum
    {
        $default = SortOrderEnum::DESC;

        if (!request()->has('sort_order')) {
            return $default;
        }

        return SortOrderEnum::tryFrom(request('sort_order')) ?? $default;
    }

    private function default(Builder $query): void
    {
        $query->orderBy($this->getSortColumn()->value, $this->getSortOrder()->value);
    }

    private function popular(Builder $query): void
    {
        /** Сортировка по популярности на стороне получения продуктов */
    }
}
