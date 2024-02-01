<?php

namespace App\Http\Resources\Catalog\Weave;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Domain\Catalog\Models\Weave;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WeaveCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;

    protected function total(): int
    {
        return Weave::where('is_active', true)->count();
    }
}
