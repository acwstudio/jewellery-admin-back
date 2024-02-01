<?php

namespace App\Http\Resources\Catalog\Product;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Domain\Catalog\Models\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;

    protected function total(): int
    {
        return Product::where('is_active', true)->count();
    }
}
