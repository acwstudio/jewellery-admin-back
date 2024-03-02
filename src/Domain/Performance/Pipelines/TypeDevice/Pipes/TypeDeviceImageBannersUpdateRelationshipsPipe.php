<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypeDevice\Pipes;

use Domain\Performance\Repositories\TypeDevice\TypeDeviceRelationsRepository;

final class TypeDeviceImageBannersUpdateRelationshipsPipe
{
    public function __construct(public TypeDeviceRelationsRepository $typeDeviceRelationsRepository)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function handle(array $data, \Closure $next)
    {
        $relationData = data_get($data, 'data.relationships.imageBanners');

        if ($relationData) {
            data_set($data, 'relation_data', $relationData);
            data_set($data, 'relation_method', 'imageBanners');

            $this->typeDeviceRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
