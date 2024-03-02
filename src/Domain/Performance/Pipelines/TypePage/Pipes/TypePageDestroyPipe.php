<?php

declare(strict_types=1);

namespace Domain\Performance\Pipelines\TypePage\Pipes;

use Domain\Performance\Repositories\TypePage\TypePageRepository;

final class TypePageDestroyPipe
{
    public function __construct(public TypePageRepository $typePageRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->typePageRepository->destroy($id);

        return $next($id);
    }
}
