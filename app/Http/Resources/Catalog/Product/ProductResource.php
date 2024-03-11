<?php

namespace App\Http\Resources\Catalog\Product;

use App\Http\Resources\Blog\BlogPost\BlogPostCollection;
use App\Http\Resources\Catalog\Price\PriceCollection;
use App\Http\Resources\Catalog\PriceCategory\PriceCategoryCollection;
use App\Http\Resources\Catalog\ProductCategory\ProductCategoryResource;
use App\Http\Resources\Catalog\Size\SizeCollection;
use App\Http\Resources\Catalog\SizeCategory\SizeCategoryCollection;
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
                'weaves'          => $this->sectionRelationships('products.weaves', WeaveCollection::class),
                'productCategory' => $this->sectionRelationships(
                    'products.product-category', ProductCategoryResource::class
                ),
//                'brand' => $this->sectionRelationships('products.brand', BrandCollection::class),
                'blogPosts' => $this->sectionRelationships('products.blog-posts', BlogPostCollection::class),
                'sizes' => $this->sectionRelationships('product.sizes', SizeCollection::class),
                'sizeCategories' => $this->sectionRelationships(
                    'products.size-categories', SizeCategoryCollection::class
                )
            ]
        ];
    }

    function relations(): array
    {
        return [
            WeaveCollection::class => $this->whenLoaded('weaves'),
            ProductCategoryResource::class => $this->whenLoaded('productCategory'),
//            BrandCollection::class => $this->whenLoaded('brand'),
            BlogPostCollection::class => $this->whenLoaded('blogPosts'),
            SizeCollection::class => $this->whenLoaded('sizes'),
            SizeCategoryCollection::class => $this->whenLoaded('sizeCategories'),
        ];
    }
}
