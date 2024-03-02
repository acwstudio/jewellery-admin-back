<?php

declare(strict_types=1);

namespace App\Http\Resources\Performance\TypeDevice;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TypeDeviceCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;
}
