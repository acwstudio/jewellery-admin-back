<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypeDevice\Pipes;

use Domain\Performance\Repositories\TypeDevice\TypeDeviceRepository;

final class TypeDeviceUpdatePipe
{
    public function __construct(public TypeDeviceRepository $typeDeviceRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->typeDeviceRepository->update($data);
        data_set($data, 'model', $model);

        return $next($data);
    }
}
