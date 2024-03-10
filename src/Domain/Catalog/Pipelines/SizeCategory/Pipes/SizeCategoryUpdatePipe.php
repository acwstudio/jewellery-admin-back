<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\SizeCategory\Pipes;

use Domain\Catalog\Repositories\SizeCategory\SizeCategoryRepository;

final class SizeCategoryUpdatePipe
{
    public function __construct(public SizeCategoryRepository $sizeCategoryRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->sizeCategoryRepository->update($data);
        data_set($data, 'model', $model);

        return $next($data);
    }
}
