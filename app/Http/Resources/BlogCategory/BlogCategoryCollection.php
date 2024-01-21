<?php

declare(strict_types=1);

namespace App\Http\Resources\BlogCategory;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Domain\Blog\Models\BlogCategory;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogCategoryCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;

    protected function total(): int
    {
        return BlogCategory::where('active', true)->count();
    }
}
