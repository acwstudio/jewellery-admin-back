<?php

declare(strict_types=1);

namespace App\Http\Resources\Blog\BlogCategory;

use App\Http\Resources\Blog\BlogPost\BlogPostCollection;
use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use Domain\Blog\Models\BlogCategory;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin BlogCategory */
class BlogCategoryResource extends JsonResource
{
    use IncludeRelatedEntitiesResourceTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'type' => BlogCategory::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'blogPosts' => $this->sectionRelationships('blog-category.blog-posts', BlogPostCollection::class),
                'parent' => $this->sectionRelationships('blog-categories.parent', BlogCategoryResource::class),
                'children' => $this->sectionRelationships('blog-category.children', BlogCategoryCollection::class),
            ]
        ];
    }

    function relations(): array
    {
        return [
            BlogPostCollection::class => $this->whenLoaded('blogPosts'),
            BlogCategoryCollection::class => $this->whenLoaded('children'),
            BlogCategoryResource::class => $this->whenLoaded('parent'),
        ];
    }
}
