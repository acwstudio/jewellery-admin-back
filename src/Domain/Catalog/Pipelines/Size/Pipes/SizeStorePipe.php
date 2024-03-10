<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Size\Pipes;

use Domain\Catalog\Repositories\Size\SizeRepository;

final class SizeStorePipe
{
    public function __construct(public SizeRepository $sizeRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->sizeRepository->store($data);
        data_set($data, 'model', $model);
        data_set($data, 'id', $model->id);

        return $next($data);
    }
}
