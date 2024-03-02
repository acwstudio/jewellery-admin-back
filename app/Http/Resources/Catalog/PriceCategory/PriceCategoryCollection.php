<?php

namespace App\Http\Resources\Catalog\PriceCategory;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PriceCategoryCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;
}
