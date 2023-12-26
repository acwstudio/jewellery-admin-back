<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Filters;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use Illuminate\Support\Collection;

class FeatureFilter
{
    public function __construct(
        public readonly ?Collection $id = null,
        public readonly ?FeatureTypeEnum $type = null,
        public readonly ?string $value = null,
        public readonly ?string $slug = null,
        public readonly ?string $query = null,
    ) {
    }
}
