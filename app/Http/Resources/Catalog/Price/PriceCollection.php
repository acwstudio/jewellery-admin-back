<?php

namespace App\Http\Resources\Catalog\Price;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PriceCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;
}
