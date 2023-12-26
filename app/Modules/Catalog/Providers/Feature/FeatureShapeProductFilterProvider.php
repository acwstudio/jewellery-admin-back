<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers\Feature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;

class FeatureShapeProductFilterProvider extends AbstractFeatureProductFilterProvider
{
    public function isStatic(): bool
    {
        return false;
    }

    public function getFilterTitle(): string
    {
        return 'Форма камня';
    }

    public function getFeatureType(): FeatureTypeEnum
    {
        return FeatureTypeEnum::SHAPE;
    }
}
