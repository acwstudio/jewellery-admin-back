<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\ProductCategory\Pipes;

use Domain\Catalog\Repositories\ProductCategory\ProductCategoryRelationsRepository;

final class ProductCategoryProductsStoreUpdateRelationshipsPipe
{
    const RELATION = 'products';

    public function __construct(public ProductCategoryRelationsRepository $productCategoryRelationsRepository)
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

            $this->productCategoryRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
