<?php

declare(strict_types=1);

namespace App\Modules\Users\Pipes;

use App\Modules\Users\Contracts\Pipes\WishlistQueryBuilderPipeContract;
use App\Modules\Users\Enums\WishlistSortColumnEnum;
use App\Packages\Enums\SortOrderEnum;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class SortWishlistQueryBuilderPipe implements WishlistQueryBuilderPipeContract
{
    public function handle(Builder $query, Closure $next): Builder
    {
        $query->orderBy($this->getSortColumn()->value, $this->getSortOrder()->value);

        return $next($query);
    }

    private function getSortColumn(): WishlistSortColumnEnum
    {
        $default = WishlistSortColumnEnum::CREATED_AT;

        if (!request()->has('sort_by')) {
            return $default;
        }

        return WishlistSortColumnEnum::tryFrom(request('sort_by')) ?? $default;
    }

    private function getSortOrder(): SortOrderEnum
    {
        $default = SortOrderEnum::DESC;

        if (!request()->has('sort_order')) {
            return $default;
        }

        return SortOrderEnum::tryFrom(request('sort_order')) ?? $default;
    }
}
