<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Blueprints;

class ProductImageUrlBlueprint
{
    public function __construct(
        public readonly string $path,
        public readonly bool $is_main = false
    ) {
    }
}
