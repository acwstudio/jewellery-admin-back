<?php

namespace App\Http\Resources\Catalog\SizeCategory;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SizeCategoryCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;
}
