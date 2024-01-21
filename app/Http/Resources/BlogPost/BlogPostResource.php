<?php

declare(strict_types=1);

namespace App\Http\Resources\BlogPost;

use App\Http\Resources\BlogCategory\BlogCategoryResource;
use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use Domain\Blog\Models\BlogPost;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin BlogPost */
class BlogPostResource extends JsonResource
{
    use IncludeRelatedEntitiesResourceTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => BlogPost::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'blogCategory' => $this->sectionRelationships(
                    'blog-posts.blog-category',
                    BlogCategoryResource::class
                )
            ]
        ];
    }

    function relations(): array
    {
        return [
            BlogCategoryResource::class => $this->whenLoaded('blogCategory'),
        ];
    }
}
