<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers\Feature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;

class FeatureBooleanProductFilterProvider extends AbstractFeatureProductFilterProvider
{
    public function isStatic(): bool
    {
        return false;
    }

    public function getFilterTitle(): string
    {
        return 'Скидки и акции';
    }

    public function getFeatureType(): FeatureTypeEnum
    {
        return FeatureTypeEnum::BOOLEAN;
    }
}
