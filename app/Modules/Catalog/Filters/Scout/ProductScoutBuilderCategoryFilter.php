<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Scout;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;
use OpenSearch\ScoutDriverPlus\Support\Query;

class ProductScoutBuilderCategoryFilter implements ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void
    {
        if (empty($value)) {
            return;
        }

        $values = explode(',', $value);

        if ($this->isNumeric($values)) {
            $this->filterById($builder, $values);
        } else {
            $this->filterBySlug($builder, $values);
        }
    }

    private function filterById(BoolQueryBuilder $builder, array $values): void
    {
        $builder->filter(
            Query::terms()->field('categories.id')->values($values)
        );
    }

    private function filterBySlug(BoolQueryBuilder $builder, array $values): void
    {
        $builder->filter(
            Query::terms()->field('categories.slug')->values($values)
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
