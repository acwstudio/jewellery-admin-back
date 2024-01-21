<?php

declare(strict_types=1);

namespace App\Http\Resources\Identifiers;

use Illuminate\Http\Resources\Json\JsonResource;

final class ApiEntityIdentifierResource extends JsonResource
{
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
            'type' => $this->resource::TYPE_RESOURCE,
        ];
    }
}
