<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Scout;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use App\Modules\Catalog\Enums\FeatureDynamicTypeEnum;
use App\Modules\Catalog\Enums\FeatureTypeEnum;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;
use OpenSearch\ScoutDriverPlus\Support\Query;

class ProductScoutBuilderFeatureFilter implements ProductScoutBuilderFilterContract
{
    public function apply(BoolQueryBuilder $builder, $value): void
    {
        if (!is_array($value)) {
            return;
        }

        foreach ($value as $key => $valueItem) {
            $featureType = FeatureTypeEnum::tryFrom($key);
            if (!$featureType instanceof FeatureTypeEnum) {
                continue;
            }
            $this->filter($builder, $featureType, $valueItem);
        }
    }

    private function filter(BoolQueryBuilder $builder, FeatureTypeEnum $featureType, $value): void
    {
        if (FeatureTypeEnum::DYNAMIC === $featureType) {
            $this->filterDynamic($builder, $value);
        } else {
            $this->filterEquals($builder, $featureType, $value);
        }
    }

    private function filterEquals(BoolQueryBuilder $builder, FeatureTypeEnum $type, $value): void
    {
        $values = explode(',', $value);

        $queryGroup = Query::terms();
        $queryGroup->field('product_features.feature_id')->values($values);

        $builder->filter($queryGroup);
    }

    private function filterDynamic(BoolQueryBuilder $builder, $dynamicFilters): void
    {
        foreach ($dynamicFilters as $key => $value) {
            $dynamicType = FeatureDynamicTypeEnum::tryFrom($key);

            if (!$dynamicType instanceof FeatureDynamicTypeEnum) {
                continue;
            }

            $this->filterByProductFeatureValue($builder, $dynamicType, $value);
        }
    }

    private function filterByProductFeatureValue(
        BoolQueryBuilder $builder,
        FeatureDynamicTypeEnum $dynamicType,
        array $data
    ): void {
        $min = $data['min'] ?? null;
        $max = $data['max'] ?? null;

        if (empty($min) && empty($max)) {
            return;
        }

        $queryGroup = Query::bool();
        $queryGroup->filter(
            Query::term()->field('product_features.feature.value')->value($dynamicType->getLabel())
        );
        $queryGroup->filter(
            Query::range()->field('product_features.value')->gte($min)->lte($max)
        );

        $builder->filter($queryGroup);
    }
}
