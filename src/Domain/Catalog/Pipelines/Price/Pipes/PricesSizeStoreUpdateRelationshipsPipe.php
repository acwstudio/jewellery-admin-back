<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Price\Pipes;

use Domain\Catalog\Repositories\Price\Relationships\PricesSizeRelationshipsRepository;

final class PricesSizeStoreUpdateRelationshipsPipe
{
    const RELATION = 'size';

    public function __construct(public PricesSizeRelationshipsRepository $repository)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function handle(array $data, \Closure $next)
    {
        $id = data_get($data, 'id');
        $dataRelationship = data_get($data, 'data.relationships.size');

        if ($dataRelationship) {
            data_set($dataRelationship, 'id', $id);
            $this->repository->update($dataRelationship);
        }

        return $next($data);
    }
}
