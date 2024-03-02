<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\PriceCategory\Pipes;

use Domain\Catalog\Repositories\PriceCategory\PriceCategoryRepository;

final class PriceCategoryDestroyPipe
{
    public function __construct(public PriceCategoryRepository $priceCategoryRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->priceCategoryRepository->destroy($id);

        return $next($id);
    }
}
