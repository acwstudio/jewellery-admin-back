<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\Banner\Pipes;

use Domain\Performance\Repositories\Banner\BannerRepository;

final class BannerUpdatePipe
{
    public function __construct(public BannerRepository $bannerRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->bannerRepository->update($data);
        data_set($data, 'model', $model);

        return $next($data);
    }
}
