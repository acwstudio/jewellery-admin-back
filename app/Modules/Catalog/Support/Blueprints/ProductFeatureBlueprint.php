<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Blueprints;

class ProductFeatureBlueprint
{
    public function __construct(
        public readonly ?string $value = null,
        public readonly ?bool $is_main = null
    ) {
    }
}
