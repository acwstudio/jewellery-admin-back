<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Weave\Pipes;

use Domain\Catalog\Repositories\Weave\WeaveRepository;

final class WeaveUpdatePipe
{
    public function __construct(public WeaveRepository $weaveRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->weaveRepository->update($data);
        data_set($data, 'model', $model);

        return $next($data);
    }
}
