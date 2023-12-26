<?php

declare(strict_types=1);

namespace App\Modules\Collections\Filters;

use App\Modules\Collections\Contracts\Filters\FilterCollectionQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class CollectionQueryBuilderNameFilter implements FilterCollectionQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        $query->where(function (Builder $query) use ($value) {
            $this->filterByName($query, $value);
            $this->filterByDescription($query, $value);
        });

        return $query;
    }

    private function filterByName(Builder $query, string $name): void
    {
        $query->where('name', 'ILIKE', "%{$name}%");
        $query->where('extended_name', 'ILIKE', "%{$name}%", 'or');
    }

    private function filterByDescription(Builder $query, string $name, string $boolean = 'or'): void
    {
        $query->where('description', 'ILIKE', "%{$name}%", $boolean);
        $query->where('extended_description', 'ILIKE', "%{$name}%", 'or');
    }
}
