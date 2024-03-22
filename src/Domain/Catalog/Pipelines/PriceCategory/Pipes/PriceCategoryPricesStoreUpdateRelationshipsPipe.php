<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\PriceCategory\Pipes;

use Domain\Catalog\Repositories\PriceCategory\PriceCategoryRelationsRepository;
use Domain\Catalog\Repositories\PriceCategory\Relationships\PriceCategoryPricesRelationshipsRepository;

final class PriceCategoryPricesStoreUpdateRelationshipsPipe
{
    public function __construct(public PriceCategoryPricesRelationshipsRepository $repository)
    {
    }

    public function handle(array $data, \Closure $next)
    {
        $id = data_get($data, 'id');
        $dataPrices = data_get($data, 'data.relationships.prices');

        if ($dataPrices) {
            $dataPrices = data_set($dataPrices, 'id', $id);
            $this->repository->update($dataPrices);
        }

        return $next($data);
    }
}
