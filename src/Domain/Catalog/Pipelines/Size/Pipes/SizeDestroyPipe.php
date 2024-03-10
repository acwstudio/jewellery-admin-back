<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Size\Pipes;

use Domain\Catalog\Repositories\Size\SizeRepository;

final class SizeDestroyPipe
{
    public function __construct(public SizeRepository $sizeRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->sizeRepository->destroy($id);

        return $next($id);
    }
}
