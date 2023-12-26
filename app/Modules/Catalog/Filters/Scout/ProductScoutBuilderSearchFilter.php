<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Scout;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;
use OpenSearch\ScoutDriverPlus\Builders\MatchQueryBuilder;
use OpenSearch\ScoutDriverPlus\Builders\TermQueryBuilder;

class ProductScoutBuilderSearchFilter implements ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void
    {
        if (empty($value)) {
            return;
        }

        $this->filter($builder, (string)$value);
        $builder->minimumShouldMatch(1);
    }

    private function filter(BoolQueryBuilder $builder, string $value): void
    {
        $this->filterSku($builder, $value);

        $fields = ['name', 'description', 'categories.title', 'feature_all'];

        foreach ($fields as $field) {
            $builder->should($this->match($field, $value));
        }
    }

    private function filterSku(BoolQueryBuilder $builder, string $value): void
    {
        $term = new TermQueryBuilder();
        $term->field('sku')->value($value);
        $builder->should($term);
    }

    private function match(string $field, string $value): MatchQueryBuilder
    {
        $queryBuilder = new MatchQueryBuilder();
        $queryBuilder->field($field)->query($value)->fuzziness('2')->operator('and');

        return $queryBuilder;
    }
}
