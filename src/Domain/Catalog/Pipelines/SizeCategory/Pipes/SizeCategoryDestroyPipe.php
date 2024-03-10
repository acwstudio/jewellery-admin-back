<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\SizeCategory\Pipes;

use Domain\Catalog\Repositories\SizeCategory\SizeCategoryRepository;

final class SizeCategoryDestroyPipe
{
    public function __construct(public SizeCategoryRepository $sizeCategoryRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->sizeCategoryRepository->destroy($id);

        return $next($id);
    }
}
