<?php

declare(strict_types=1);

namespace App\Http\Resources\Performance\TypeDevice;

use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use App\Http\Resources\Performance\ImageBanner\ImageBannerCollection;
use Domain\Performance\Models\TypeDevice;
use Illuminate\Http\Resources\Json\JsonResource;

class TypeDeviceResource extends JsonResource
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
            'type' => TypeDevice::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'imageBanners' => $this->sectionRelationships('type-device.image-banners', ImageBannerCollection::class),
            ]
        ];
    }

    function relations(): array
    {
        return [
            ImageBannerCollection::class => $this->whenLoaded('imageBanners')
        ];
    }
}
