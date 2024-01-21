<?php

declare(strict_types=1);

namespace App\Http\Resources\Identifiers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiEntityIdentifierCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return [
//            'data'     => $this->collection,
//            'meta' => [
//                'total' => app($this->resource[0]->resource::class)->all()->count() ?? null
//                'total' => $this->resource->count() ?? null
//            ]
//        ];
    }
}
