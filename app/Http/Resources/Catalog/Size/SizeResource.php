<?php

namespace App\Http\Resources\Catalog\Size;

use App\Http\Resources\Catalog\Price\PriceCollection;
use App\Http\Resources\Catalog\PriceCategory\PriceCategoryCollection;
use App\Http\Resources\Catalog\Product\ProductResource;
use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use App\Http\Resources\Catalog\SizeCategory\SizeCategoryResource;
use Domain\Catalog\Models\Size;
use Illuminate\Http\Resources\Json\JsonResource;

class SizeResource extends JsonResource
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
            'type' => Size::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'product' => $this->sectionRelationships('sizes.product', ProductResource::class),
                'sizeCategory' => $this->sectionRelationships('sizes.size-category', SizeCategoryResource::class),
                'prices' => $this->sectionRelationships('size.prices', PriceCollection::class),
                'priceCategories' => $this->sectionRelationships('sizes.price-categories', PriceCategoryCollection::class),
            ]
        ];
    }

    function relations(): array
    {
        return [
            ProductResource::class => $this->whenLoaded('product'),
            SizeCategoryResource::class => $this->whenLoaded('sizeCategory'),
            PriceCollection::class => $this->whenLoaded('prices'),
            PriceCategoryCollection::class => $this->whenLoaded('priceCategories'),
        ];
    }
}
