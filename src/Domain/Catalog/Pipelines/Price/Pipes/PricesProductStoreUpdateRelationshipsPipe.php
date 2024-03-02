<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Price\Pipes;

use Domain\Catalog\Repositories\Price\PriceRelationsRepository;

final class PricesProductStoreUpdateRelationshipsPipe
{
    public function __construct(public PriceRelationsRepository $priceRelationsRepository)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function handle(array $data, \Closure $next)
    {
        $relationData = data_get($data, 'data.relationships.product');

        if ($relationData) {
            data_set($data, 'relation_data', $relationData);
            data_set($data, 'relation_method', 'product');

            $this->priceRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
