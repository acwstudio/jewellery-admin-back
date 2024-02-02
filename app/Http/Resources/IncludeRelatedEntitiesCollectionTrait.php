<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Modules_api_v2\Blog\Models\BlogCategory;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Collection;

trait IncludeRelatedEntitiesCollectionTrait
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data'     => $this->collection,
            'included' => $this->mergeIncludedRelations($request),
            'meta' => [
//                'total' => $this->total() ?? null
//                'total' => $this->collection->count()
            ]
        ];
    }

    /**
     * @param $request
     * @return MissingValue|Collection
     */
    private function mergeIncludedRelations($request): MissingValue|Collection
    {
        $includes = $this->collection->flatMap(function ($resource) use ($request) {
            return $resource->included($request);
        })->unique('glob_id')->values();

        return $includes->isNotEmpty() ? $includes : new MissingValue();
    }

//    abstract protected function total(): int;
}
