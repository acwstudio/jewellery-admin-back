<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypePage\Pipes;

use Domain\Performance\Repositories\TypePage\TypePageRepository;

final class TypePageStorePipe
{
    public function __construct(public TypePageRepository $typePageRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->typePageRepository->store($data);
        data_set($data, 'model', $model);
        data_set($data, 'id', $model->id);

        return $next($data);
    }
}
