<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypeBanner\Pipes;

use Domain\Performance\Repositories\TypeBanner\TypeBannerRepository;

final class TypeBannerDestroyPipe
{
    public function __construct(public TypeBannerRepository $typeBannerRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->typeBannerRepository->destroy($id);

        return $next($id);
    }
}
