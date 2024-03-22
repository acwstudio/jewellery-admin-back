<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\PriceCategory\Pipes;

use Domain\Catalog\Repositories\PriceCategory\Relationships\PriceCategoriesSizesRelationshipsRepository;

final class PriceCategoriesSizesStoreUpdateRelationshipsPipe
{
    public function __construct(public PriceCategoriesSizesRelationshipsRepository $repository)
    {
    }

    public function handle(array $data, \Closure $next)
    {
        $id = data_get($data, 'id');
        $dataSize = data_get($data, 'data.relationships.sizes');

        if ($dataSize) {
            $dataSize = data_set($dataSize, 'id', $id);
            $this->repository->update($dataSize);
        }

        return $next($data);
    }
}
