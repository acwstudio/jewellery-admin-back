<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypeBanner\Pipes;

use Domain\Performance\Repositories\TypeBanner\TypeBannerRepository;

final class TypeBannerUpdatePipe
{
    public function __construct(public TypeBannerRepository $typeBannerRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->typeBannerRepository->update($data);
        data_set($data, 'model', $model);

        return $next($data);
    }
}
