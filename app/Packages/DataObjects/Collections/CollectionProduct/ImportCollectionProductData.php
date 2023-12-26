<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Collections\CollectionProduct;

use Spatie\LaravelData\Data;

class ImportCollectionProductData extends Data
{
    public function __construct(
        public readonly int $collection_id,
        public readonly array $product_ids,
        public readonly array $category_ids,
    ) {
    }
}
