<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderCategoryFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        $values = explode(',', $value);

        if ($this->isNumeric($values)) {
            $this->filterById($query, $values);
        } else {
            $this->filterBySlug($query, $values);
        }

        return $query;
    }

    private function filterById(Builder $query, array $values): Builder
    {
        return $query->whereHas(
            'categories',
            fn (Builder $categoryBuilder) => $categoryBuilder
                ->whereIn('categories.id', $values)
        );
    }

    private function filterBySlug(Builder $query, array $values): Builder
    {
        return $query->whereHas(
            'categories',
            fn (Builder $categoryBuilder) => $categoryBuilder
                ->whereIn('categories.slug', $values)
        );
    }

    private function isNumeric(array $values): bool
    {
        foreach ($values as $value) {
            if (!is_numeric($value)) {
                return false;
            }
        }

        return true;
    }
}
