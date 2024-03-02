<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypePage\Pipes;

use Domain\Performance\Repositories\TypePage\TypePageRepository;

final class TypePageUpdatePipe
{
    public function __construct(public TypePageRepository $typePageRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->typePageRepository->update($data);
        data_set($data, 'model', $model);

        return $next($data);
    }
}
