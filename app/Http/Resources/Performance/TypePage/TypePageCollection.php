<?php

declare(strict_types=1);

namespace App\Http\Resources\Performance\TypePage;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TypePageCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;
}
