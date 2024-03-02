<?php

declare(strict_types=1);

namespace App\Http\Resources\Performance\TypeBanner;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TypeBannerCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;
}
