<?php

declare(strict_types=1);

namespace App\Http\Resources\Performance\Banner;

use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use App\Http\Resources\Performance\ImageBanner\ImageBannerCollection;
use App\Http\Resources\Performance\TypeBanner\TypeBannerResource;
use App\Http\Resources\Performance\TypePage\TypePageResource;
use Domain\Performance\Models\Banner;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Banner */
class BannerResource extends JsonResource
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
            'type' => Banner::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'typeBanner'   => $this->sectionRelationships('banners.type-banner', TypeBannerResource::class),
                'imageBanners' => $this->sectionRelationships('banners.image-banners', ImageBannerCollection::class),
                'typePage'     => $this->sectionRelationships('banners.type-page', TypePageResource::class),
            ]
        ];
    }

    function relations(): array
    {
        return [
            TypeBannerResource::class    => $this->whenLoaded('typeBanner'),
            ImageBannerCollection::class => $this->whenLoaded('imageBanners'),
            TypePageResource::class      => $this->whenLoaded('typePage')
        ];
    }
}
