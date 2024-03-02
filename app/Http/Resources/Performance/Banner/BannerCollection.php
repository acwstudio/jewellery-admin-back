<?php

declare(strict_types=1);

namespace App\Http\Resources\Performance\Banner;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BannerCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;
}
