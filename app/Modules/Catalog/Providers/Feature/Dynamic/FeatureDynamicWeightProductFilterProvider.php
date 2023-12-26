<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers\Feature\Dynamic;

use App\Modules\Catalog\Enums\FeatureDynamicTypeEnum;

class FeatureDynamicWeightProductFilterProvider extends AbstractFeatureDynamicProductFilterProvider
{
    public function getDynamicType(): FeatureDynamicTypeEnum
    {
        return FeatureDynamicTypeEnum::WEIGHT;
    }
}
