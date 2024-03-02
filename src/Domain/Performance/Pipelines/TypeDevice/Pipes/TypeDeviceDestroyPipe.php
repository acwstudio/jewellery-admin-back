<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypeDevice\Pipes;

use Domain\Performance\Repositories\TypeDevice\TypeDeviceRepository;

final class TypeDeviceDestroyPipe
{
    public function __construct(public TypeDeviceRepository $typeDeviceRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->typeDeviceRepository->destroy($id);

        return $next($id);
    }
}
