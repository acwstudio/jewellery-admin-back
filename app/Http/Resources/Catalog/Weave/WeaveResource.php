<?php

namespace App\Http\Resources\Catalog\Weave;

use App\Http\Resources\Catalog\Product\ProductCollection;
use App\Http\Resources\Catalog\Product\ProductResource;
use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use Domain\Catalog\Models\Weave;
use Illuminate\Http\Resources\Json\JsonResource;

class WeaveResource extends JsonResource
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
            'type' => Weave::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'products' => $this->sectionRelationships('weaves.products', ProductCollection::class)
            ]
        ];
    }

    function relations(): array
    {
        return [
            ProductCollection::class => $this->whenLoaded('products'),
        ];
    }
}
