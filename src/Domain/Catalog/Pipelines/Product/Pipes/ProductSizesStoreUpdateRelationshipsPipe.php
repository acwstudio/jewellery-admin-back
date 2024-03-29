<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Product\Pipes;

use Domain\Catalog\Repositories\Product\ProductRelationsRepository;

final class ProductSizesStoreUpdateRelationshipsPipe
{
    const RELATION = 'sizes';

    public function __construct(public ProductRelationsRepository $productRelationsRepository)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function handle(array $data, \Closure $next)
    {
        $relationData = data_get($data, 'data.relationships.' . self::RELATION);

        if ($relationData) {
            data_set($data, 'relation_data', $relationData);
            data_set($data, 'relation_method', self::RELATION);

            $this->productRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
