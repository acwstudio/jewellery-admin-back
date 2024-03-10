<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\PriceCategory\Pipes;

use Domain\Catalog\Repositories\PriceCategory\PriceCategoryRelationsRepository;

final class PriceCategoriesSizesStoreUpdateRelationshipsPipe
{
    const RELATION = 'sizes';

    public function __construct(public PriceCategoryRelationsRepository $priceCategoryRelationsRepository)
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

            $this->priceCategoryRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
