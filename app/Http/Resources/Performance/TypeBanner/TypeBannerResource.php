<?php

declare(strict_types=1);

namespace App\Http\Resources\Performance\TypeBanner;

use App\Http\Resources\IncludeRelatedEntitiesResourceTrait;
use App\Http\Resources\Performance\Banner\BannerCollection;
use Domain\Performance\Models\TypeBanner;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin TypeBanner */
class TypeBannerResource extends JsonResource
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
            'type' => TypeBanner::TYPE_RESOURCE,
            'attributes' => $this->attributeItems(),
            'relationships' => [
                'banners' => $this->sectionRelationships('type-banner.banners', BannerCollection::class),
            ]
        ];
    }

    function relations(): array
    {
        return [
            BannerCollection::class => $this->whenLoaded('banners')
        ];
    }
}
