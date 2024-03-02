<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypeBanner\Pipes;

use Domain\Performance\Repositories\TypeBanner\TypeBannerRelationsRepository;

final class TypeBannerBannersUpdateRelationshipsPipe
{
    public function __construct(public TypeBannerRelationsRepository $typeBannerRelationsRepository)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function handle(array $data, \Closure $next)
    {
        $relationData = data_get($data, 'data.relationships.banners');

        if ($relationData) {
            data_set($data, 'relation_data', $relationData);
            data_set($data, 'relation_method', 'banners');

            $this->typeBannerRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
