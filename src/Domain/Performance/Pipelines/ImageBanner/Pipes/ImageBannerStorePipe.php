<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\ImageBanner\Pipes;

use Domain\Performance\Repositories\ImageBanner\ImageBannerRepository;

final class ImageBannerStorePipe
{
    public function __construct(public ImageBannerRepository $imageBannerRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->imageBannerRepository->store($data);
        data_set($data, 'model', $model);
        data_set($data, 'id', $model->id);

        return $next($data);
    }
}
