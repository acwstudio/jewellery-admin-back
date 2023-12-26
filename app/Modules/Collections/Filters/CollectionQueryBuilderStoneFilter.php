<?php

declare(strict_types=1);

namespace App\Modules\Collections\Filters;

use App\Modules\Collections\Contracts\Filters\FilterCollectionQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class CollectionQueryBuilderStoneFilter implements FilterCollectionQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (is_numeric($value)) {
            $this->filterById($query, (int)$value);
        }
        return $query;
    }

    private function filterById(Builder $query, int $id): void
    {
        $query->whereHas(
            'stones',
            fn (Builder $builder) => $builder->where('stone_id', '=', $id)
        );
    }
}
