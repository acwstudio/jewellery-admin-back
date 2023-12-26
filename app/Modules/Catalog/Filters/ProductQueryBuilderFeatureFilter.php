<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use App\Modules\Catalog\Enums\FeatureDynamicTypeEnum;
use App\Modules\Catalog\Enums\FeatureTypeEnum;
use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilderFeatureFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        if (!is_array($value)) {
            return $query;
        }

        foreach ($value as $key => $valueItem) {
            $featureType = FeatureTypeEnum::tryFrom($key);
            if (!$featureType instanceof FeatureTypeEnum) {
                continue;
            }
            $this->filter($query, $featureType, $valueItem);
        }

        return $query;
    }

    private function filter(Builder $query, FeatureTypeEnum $featureType, $value): void
    {
        if (FeatureTypeEnum::DYNAMIC === $featureType) {
            $this->filterDynamic($query, $value);
        } else {
            $this->filterEquals($query, $featureType, $value);
        }
    }

    private function filterEquals(Builder $query, FeatureTypeEnum $type, $value): void
    {
        $arrayValue = explode(',', (string)$value);

        $query->whereHas(
            'productFeatures',
            fn (Builder $productFeatureBuilder) => $productFeatureBuilder
                ->whereHas(
                    'feature',
                    fn (Builder $featureBuilder) => $featureBuilder
                        ->whereIn('id', $arrayValue)
                        ->where('type', '=', $type)
                )
        );
    }

    private function filterDynamic(Builder $query, $dynamicFilters): void
    {
        foreach ($dynamicFilters as $key => $value) {
            $dynamicType = FeatureDynamicTypeEnum::tryFrom($key);

            if (!$dynamicType instanceof FeatureDynamicTypeEnum) {
                continue;
            }

            $this->filterByProductFeatureValue($query, $dynamicType, $value);
        }
    }

    private function filterByProductFeatureValue(Builder $query, FeatureDynamicTypeEnum $dynamicType, array $data): void
    {
        $min = $data['min'] ?? null;
        $max = $data['max'] ?? null;

        if (empty($min) && empty($max)) {
            return;
        }

        $castValue = "CASE WHEN value~E'^\([0-9]*[.])?[0-9]+$' THEN CAST (value AS DOUBLE PRECISION) ELSE NULL END";

        $query->whereHas(
            'productFeatures',
            fn (Builder $productFeatureBuilder) => $productFeatureBuilder
                ->whereHas(
                    'feature',
                    fn (Builder $featureBuilder) => $featureBuilder
                        ->where('type', '=', FeatureTypeEnum::DYNAMIC)
                        ->where('value', '=', $dynamicType->getLabel())
                )
                ->whereRaw("{$castValue} >= {$min} AND {$castValue} <= {$max}")
        );
    }
}
