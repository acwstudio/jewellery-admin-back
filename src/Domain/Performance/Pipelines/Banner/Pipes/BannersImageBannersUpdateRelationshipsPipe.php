<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\Banner\Pipes;

use Domain\Performance\Repositories\Banner\BannerRelationsRepository;

final class BannersImageBannersUpdateRelationshipsPipe
{
    public function __construct(public BannerRelationsRepository $bannerRelationsRepository)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function handle(array $data, \Closure $next): mixed
    {
        $relationData = data_get($data, 'data.relationships.imageBanners');

        if ($relationData) {
            data_set($data, 'relation_data', $relationData);
            data_set($data, 'relation_method', 'imageBanners');

//            $data['relation_data'] = ['data' => data_get($data, 'data.relationships.imageBanners.*.data')];

            $this->bannerRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
