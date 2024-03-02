<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypeBanner\Pipes;

use Domain\Performance\Repositories\TypeBanner\TypeBannerRepository;

final class TypeBannerStorePipe
{
    public function __construct(public TypeBannerRepository $typeBannerRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->typeBannerRepository->store($data);
        data_set($data, 'model', $model);
        data_set($data, 'id', $model->id);

        return $next($data);
    }
}
