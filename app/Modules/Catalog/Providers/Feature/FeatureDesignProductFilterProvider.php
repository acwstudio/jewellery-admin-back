<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers\Feature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;

class FeatureDesignProductFilterProvider extends AbstractFeatureProductFilterProvider
{
    public function isStatic(): bool
    {
        return true;
    }

    public function getFilterTitle(): string
    {
        return 'Дизайн';
    }

    public function getFeatureType(): FeatureTypeEnum
    {
        return FeatureTypeEnum::DESIGN;
    }
}