<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\PriceCategory\Pipes;

use Domain\Catalog\Repositories\PriceCategory\PriceCategoryRepository;

final class PriceCategoryUpdatePipe
{
    public function __construct(public PriceCategoryRepository $priceCategoryRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $this->priceCategoryRepository->update($data);

//        data_set($data, 'model', $model);

        return $next($data);
    }
}
