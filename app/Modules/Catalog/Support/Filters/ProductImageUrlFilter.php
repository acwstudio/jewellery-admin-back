<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Support\Filters;

use Illuminate\Support\Collection;

class ProductImageUrlFilter
{
    public function __construct(
        public readonly ?Collection $id = null,
        public readonly ?int $product_id = null,
        public readonly ?string $path = null,
        public readonly ?bool $is_main = null,
    ) {
    }
}
