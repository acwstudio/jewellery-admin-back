<?php

namespace App\Http\Resources\Catalog\Product;

use App\Http\Resources\Catalog\Weave\WeaveCollection;
use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use Domain\Catalog\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'type' => Product::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'weaves' => $this->sectionRelationships('products.weaves', WeaveCollection::class)
            ]
        ];
    }

    function relations(): array
    {
        return [
            WeaveCollection::class => $this->whenLoaded('weaves'),
        ];
    }
}
