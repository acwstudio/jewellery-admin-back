<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Scout;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;
use OpenSearch\ScoutDriverPlus\Support\Query;

class ProductScoutBuilderIdFilter implements ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void
    {
        if (empty($value)) {
            return;
        }

        if (is_array($value)) {
            $this->filterByIds($builder, $value);
        } else {
            $this->filterById($builder, $value);
        }
    }

    private function filterById(BoolQueryBuilder $builder, int $id): void
    {
        $builder->filter(
            Query::term()->field('id')->value($id)
        );
    }

    private function filterByIds(BoolQueryBuilder $builder, array $ids): void
    {
        $builder->filter(
            Query::terms()->field('id')->values($ids)
        );
    }
}
