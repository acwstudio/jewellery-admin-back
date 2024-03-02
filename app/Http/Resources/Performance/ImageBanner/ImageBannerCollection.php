<?php

declare(strict_types=1);

namespace App\Http\Resources\Performance\ImageBanner;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ImageBannerCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;
}
