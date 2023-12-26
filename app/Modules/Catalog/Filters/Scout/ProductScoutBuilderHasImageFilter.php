<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Scout;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;
use OpenSearch\ScoutDriverPlus\Support\Query;

class ProductScoutBuilderHasImageFilter implements ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void
    {
        $hasImage = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (!is_bool($hasImage)) {
            throw new \Exception('The has_image parameter must be boolean.');
        }

        if ($hasImage) {
            $this->filterHasImage($builder);
        }
    }

    private function filterHasImage(BoolQueryBuilder $builder): void
    {
        $builder->filter(
            Query::term()->field('image_urls.is_main')->value(true)
        );
    }
}
