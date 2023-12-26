<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers\Feature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;

class FeatureProbeProductFilterProvider extends AbstractFeatureProductFilterProvider
{
    public function isStatic(): bool
    {
        return true;
    }

    public function getFilterTitle(): string
    {
        return 'Проба';
    }

    public function getFeatureType(): FeatureTypeEnum
    {
        return FeatureTypeEnum::PROBE;
    }
}
