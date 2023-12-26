<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Catalog\Product;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class ImportProductData extends Data
{
    public function __construct(
        public readonly string $external_id,
        public readonly string $sku,
        public readonly string $name,
        public readonly string $description,
        public readonly Collection $productFeatures,
        public readonly Collection $productOffers,
        public readonly Collection $productImageUrls,
        public readonly Collection $productVideoUrls,
        public readonly Collection $productSubCategories,
        public readonly bool $is_active = true,
        public readonly ?string $productCategory = null,
        public readonly ?string $siteName = null
    ) {
    }
}
