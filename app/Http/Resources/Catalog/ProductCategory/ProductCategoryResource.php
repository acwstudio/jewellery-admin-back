<?php

namespace App\Http\Resources\Catalog\ProductCategory;

use App\Http\Resources\Catalog\Product\ProductCollection;
use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use Domain\Catalog\Models\ProductCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
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
            'type' => ProductCategory::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'products' => $this->sectionRelationships('product-category.products', ProductCollection::class),
                'parent' => $this->sectionRelationships('product-categories.parent', ProductCategoryResource::class),
                'children' => $this->sectionRelationships('product-category.children', ProductCategoryCollection::class),
            ]
        ];
    }

    function relations(): array
    {
        return [
            ProductCollection::class => $this->whenLoaded('products'),
            ProductCategoryCollection::class => $this->whenLoaded('children'),
            ProductCategoryResource::class => $this->whenLoaded('parent'),
        ];
    }
}
