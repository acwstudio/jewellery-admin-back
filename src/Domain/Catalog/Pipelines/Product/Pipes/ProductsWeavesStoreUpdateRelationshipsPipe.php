<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Product\Pipes;

use Domain\Catalog\Repositories\Product\ProductRelationsRepository;

final class ProductsWeavesStoreUpdateRelationshipsPipe
{
    public function __construct(public ProductRelationsRepository $productRelationsRepository)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function handle(array $data, \Closure $next)
    {
        $relationData = data_get($data, 'data.relationships.weaves');

        if ($relationData) {
            data_set($data, 'relation_data', $relationData);
            data_set($data, 'relation_method', 'weaves');

            $this->productRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
