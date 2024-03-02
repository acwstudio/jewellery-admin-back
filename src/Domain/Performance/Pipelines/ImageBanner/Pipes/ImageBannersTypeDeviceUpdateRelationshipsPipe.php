<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\ImageBanner\Pipes;

use Domain\Performance\Repositories\ImageBanner\ImageBannerRelationsRepository;

final class ImageBannersTypeDeviceUpdateRelationshipsPipe
{
    public function __construct(public ImageBannerRelationsRepository $imageBannerRelationsRepository)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function handle(array $data, \Closure $next): mixed
    {
        $relationData = data_get($data, 'data.relationships.typeDevice');

        if ($relationData) {
            data_set($data, 'relation_data', $relationData);
            data_set($data, 'relation_method', 'typeDevice');

            $this->imageBannerRelationsRepository->updateRelations($data);
        }

        return $next($data);
    }
}
