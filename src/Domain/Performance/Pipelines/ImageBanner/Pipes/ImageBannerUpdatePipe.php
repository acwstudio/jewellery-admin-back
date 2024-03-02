<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\ImageBanner\Pipes;

use Domain\Performance\Repositories\ImageBanner\ImageBannerRepository;

final class ImageBannerUpdatePipe
{
    public function __construct(public ImageBannerRepository $imageBannerRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->imageBannerRepository->update($data);
        data_set($data, 'model', $model);

        return $next($data);
    }
}
