<?php

declare(strict_types=1);

namespace App\Http\Resources\Performance\ImageBanner;

use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use App\Http\Resources\Performance\Banner\BannerCollection;
use App\Http\Resources\Performance\TypeDevice\TypeDeviceResource;
use Domain\Performance\Models\ImageBanner;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageBannerResource extends JsonResource
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
            'type' => ImageBanner::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'banners' => $this->sectionRelationships('image-banners.banners', BannerCollection::class),
                'typeDevice' => $this->sectionRelationships('image-banners.type-device', TypeDeviceResource::class),
            ]
        ];
    }

    function relations(): array
    {
        return [
            TypeDeviceResource::class => $this->whenLoaded('typeDevice'),
            BannerCollection::class   => $this->whenLoaded('banners')
        ];
    }
}
