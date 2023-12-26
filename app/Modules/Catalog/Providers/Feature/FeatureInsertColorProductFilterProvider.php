<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers\Feature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;

class FeatureInsertColorProductFilterProvider extends AbstractFeatureProductFilterProvider
{
    public function getFilterTitle(): string
    {
        return 'Цвет вставки';
    }

    public function getFeatureType(): FeatureTypeEnum
    {
        return FeatureTypeEnum::INSERT_COLOR;
    }
}
