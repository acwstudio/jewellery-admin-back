<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\SizeCategory\Pipes;

use Domain\Catalog\Repositories\SizeCategory\SizeCategoryRelationsRepository;

final class SizeCategorySizesStoreUpdateRelationshipsPipe
{
    const RELATION = 'sizes';

    public function __construct(public SizeCategoryRelationsRepository $sizeCategoryRelationsRepository)
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

            $this->sizeCategoryRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
