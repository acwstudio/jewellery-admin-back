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
        $dataRelationship = data_get($data, 'data.relationships.sizes');

        if ($dataRelationship) {
            data_set($dataRelationship, 'id', $id);
            $this->repository->update($dataRelationship);
        }

        return $next($data);
    }
}
