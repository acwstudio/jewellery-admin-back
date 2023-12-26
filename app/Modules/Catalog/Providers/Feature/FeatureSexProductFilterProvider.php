<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers\Feature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;

class FeatureSexProductFilterProvider extends AbstractFeatureProductFilterProvider
{
    public function isStatic(): bool
    {
        return true;
    }

    public function getFilterTitle(): string
    {
        return 'Для кого';
    }

    public function getFeatureType(): FeatureTypeEnum
    {
        return FeatureTypeEnum::SEX;
    }
}
