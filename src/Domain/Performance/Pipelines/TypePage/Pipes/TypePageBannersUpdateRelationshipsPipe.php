<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypePage\Pipes;

use Domain\Performance\Repositories\TypePage\TypePageRelationsRepository;

final class TypePageBannersUpdateRelationshipsPipe
{
    public function __construct(public TypePageRelationsRepository $typePageRelationsRepository)
    {
    }

    public function handle(array $data, \Closure $next)
    {
        $relationData = data_get($data, 'data.relationships.banners');

        if ($relationData) {
            data_set($data, 'relation_data', $relationData);
            data_set($data, 'relation_method', 'banners');

            $this->typePageRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
