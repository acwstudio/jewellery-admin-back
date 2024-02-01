<?php

namespace App\Http\Resources\Catalog\ProductCategory;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Domain\Catalog\Models\ProductCategory;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCategoryCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;

    protected function total(): int
    {
        return ProductCategory::where('is_active', true)->count();
    }
}
