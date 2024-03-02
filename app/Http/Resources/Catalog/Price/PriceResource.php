<?php

namespace App\Http\Resources\Catalog\Price;

use App\Http\Resources\Catalog\PriceCategory\PriceCategoryResource;
use App\Http\Resources\Catalog\Product\ProductResource;
use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use Domain\Catalog\Models\Price;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
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
            'type' => Price::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'product'          => $this->sectionRelationships('prices.product', ProductResource::class),
                'priceCategory' => $this->sectionRelationships(
                    'prices.price-category', PriceCategoryResource::class
                )
            ]
        ];
    }

    function relations(): array
    {
        return [
            PriceCategoryResource::class => $this->whenLoaded('priceCategory'),
            ProductResource::class => $this->whenLoaded('product'),
        ];
    }
}
