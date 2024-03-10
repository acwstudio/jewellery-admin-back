<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Size\Pipes;

use Domain\Catalog\Repositories\Size\SizeRelationsRepository;

final class SizesSizeCategoryStoreUpdateRelationshipsPipe
{
    const RELATION = 'sizeCategory';

    public function __construct(public SizeRelationsRepository $sizeRelationsRepository)
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

            $this->sizeRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
