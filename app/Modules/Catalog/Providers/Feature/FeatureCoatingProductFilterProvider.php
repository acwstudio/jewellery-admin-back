<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers\Feature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;

class FeatureCoatingProductFilterProvider extends AbstractFeatureProductFilterProvider
{
    public function getFilterTitle(): string
    {
        return 'Покрытие';
    }

    public function getFeatureType(): FeatureTypeEnum
    {
        return FeatureTypeEnum::COATING;
    }
}
