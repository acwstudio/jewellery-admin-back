<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product\ImageUrl;

use Spatie\LaravelData\Data;

class ImportProductImageUrlData extends Data
{
    public function __construct(
        public readonly string $path,
        public readonly bool $is_main = false
    ) {
    }
}
