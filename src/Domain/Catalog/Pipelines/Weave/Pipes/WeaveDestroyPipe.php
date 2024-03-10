<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Weave\Pipes;

use Domain\Catalog\Repositories\Weave\WeaveRepository;

final class WeaveDestroyPipe
{
    public function __construct(public WeaveRepository $weaveRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->weaveRepository->destroy($id);

        return $next($id);
    }
}
