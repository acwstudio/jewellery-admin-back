<?php

declare(strict_types=1);

namespace App\Modules\Orders\Filters;

use App\Modules\Orders\Contracts\Filters\OrderQueryBuilderFilterContract;
use Illuminate\Database\Eloquent\Builder;

class OrderQueryBuilderUserIdFilter implements OrderQueryBuilderFilterContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        $this->filter($query, $value);
        return $query;
    }

    private function filter(Builder $query, string $value): void
    {
        $query->where('user_id', '=', $value);
    }
}
