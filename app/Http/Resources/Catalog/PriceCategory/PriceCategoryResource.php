<?php

namespace App\Http\Resources\Catalog\PriceCategory;

use App\Http\Resources\Catalog\Price\PriceCollection;
use App\Http\Resources\Catalog\Product\ProductCollection;
use App\Http\Resources\Catalog\Size\SizeCollection;
use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use Domain\Catalog\Models\PriceCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceCategoryResource extends JsonResource
{
    use IncludeRelatedEntitiesResourceTrait;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => PriceCategory::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'sizes' => $this->sectionRelationships('price-categories.sizes', SizeCollection::class),
                'prices' => $this->sectionRelationships('price-category.prices', PriceCollection::class),
            ]
        ];
    }

    function relations(): array
    {
        return [
            SizeCollection::class => $this->whenLoaded('sizes'),
            PriceCollection::class => $this->whenLoaded('prices'),
        ];
    }
}
