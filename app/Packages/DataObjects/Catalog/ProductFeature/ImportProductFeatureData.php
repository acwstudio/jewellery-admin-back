<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\ProductFeature;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ImportProductFeatureData extends Data
{
    public function __construct(
        public readonly FeatureTypeEnum $type,
        public readonly string $typeValue,
        public readonly ?string $value = null,
        public readonly ?bool $is_main = null,
        public readonly Collection $children = new Collection([]),
    ) {
    }
}
