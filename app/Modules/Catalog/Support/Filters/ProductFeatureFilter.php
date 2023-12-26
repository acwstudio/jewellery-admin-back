<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Filters;

use Illuminate\Support\Collection;

class ProductFeatureFilter
{
    public function __construct(
        public readonly ?Collection $uuid = null,
        public readonly ?int $product_id = null,
        public readonly ?int $feature_id = null,
        public readonly ?string $value = null,
        public readonly ?string $parent_uuid = null,
        public readonly ?bool $is_main = null,
    ) {
    }
}
