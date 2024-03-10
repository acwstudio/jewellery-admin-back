<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Weave\Pipes;

use Domain\Catalog\Repositories\Weave\WeaveRelationsRepository;

final class WeavesProductsStoreUpdateRelationshipsPipe
{
    const RELATION = 'products';

    public function __construct(public WeaveRelationsRepository $weaveRelationsRepository)
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

            $this->weaveRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
