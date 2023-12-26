<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class MonolithProductFilterData extends Data
{
    public function __construct(
        public readonly string $sku,
        public readonly ?string $name,
        public readonly ?string $description,
        public readonly bool $isActive,
        public readonly Collection $productFeatures,
        public readonly string $parentCategory,
        public readonly array $childCategories
    ) {
    }
}
