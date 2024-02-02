<?php

declare(strict_types=1);

namespace App\Http\Resources\Blog\BlogCategory;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Domain\Blog\Models\BlogCategory;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogCategoryCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;
}
