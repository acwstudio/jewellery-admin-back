<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\ImageBanner\Pipes;

use Domain\Performance\Repositories\ImageBanner\ImageBannerRepository;

final class ImageBannerDestroyPipe
{
    public function __construct(public ImageBannerRepository $typeBannerRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->typeBannerRepository->destroy($id);

        return $next($id);
    }
}
