<?php

declare(strict_types=1);

namespace App\Http\Resources\Blog\BlogPost;

use App\Http\Resources\IncludeRelatedEntitiesCollectionTrait;
use Domain\Blog\Models\BlogPost;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogPostCollection extends ResourceCollection
{
    use IncludeRelatedEntitiesCollectionTrait;

    protected function total(): int
    {
        return BlogPost::where('active', true)->count();
    }
}
