<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\Banner\Pipes;

use Domain\Performance\Repositories\Banner\BannerRepository;

final class BannerDestroyPipe
{
    public function __construct(public BannerRepository $bannerRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->bannerRepository->destroy($id);

        return $next($id);
    }
}
