<?php

namespace App\Http\Resources\Catalog\SizeCategory;

use App\Http\Resources\Catalog\Product\ProductCollection;
use App\Http\Resources\Catalog\Size\SizeCollection;
use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use Domain\Catalog\Models\SizeCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class SizeCategoryResource extends JsonResource
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
            'type' => SizeCategory::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'products' => $this->sectionRelationships('size-categories.products', ProductCollection::class),
                'sizes' => $this->sectionRelationships('size-category.sizes', SizeCollection::class),
            ]
        ];
    }

    function relations(): array
    {
        return [
            ProductCollection::class => $this->whenLoaded('products'),
            SizeCollection::class => $this->whenLoaded('sizes'),
        ];
    }
}
