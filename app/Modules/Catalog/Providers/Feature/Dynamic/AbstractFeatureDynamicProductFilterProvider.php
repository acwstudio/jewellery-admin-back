<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers\Feature\Dynamic;

use App\Modules\Catalog\Enums\FeatureDynamicTypeEnum;
use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Providers\Feature\AbstractFeatureProductFilterProvider;
use App\Packages\Enums\FilterTypeEnum;

abstract class AbstractFeatureDynamicProductFilterProvider extends AbstractFeatureProductFilterProvider
{
    public function isStatic(): bool
    {
        return false;
    }

    abstract public function getDynamicType(): FeatureDynamicTypeEnum;

    public function getFilterTitle(): string
    {
        return $this->getDynamicType()->getLabel();
    }

    public function getFilterName(): string
    {
        return "feature[{$this->getFeatureType()->value}][{$this->getDynamicType()->value}]";
    }

    public function getFeatureType(): FeatureTypeEnum
    {
        return FeatureTypeEnum::DYNAMIC;
    }

    public function getFilterType(): FilterTypeEnum
    {
        return FilterTypeEnum::NUM;
    }

    public function getFeatureValue(): string
    {
        return $this->getDynamicType()->getLabel();
    }
}
