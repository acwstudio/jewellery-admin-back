<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\PriceCategory\Pipes;

use Domain\Catalog\Repositories\PriceCategory\PriceCategoryRepository;

final class PriceCategoryStorePipe
{
    public function __construct(public PriceCategoryRepository $productRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->productRepository->store($data);

        data_set($data, 'model', $model);
        data_set($data, 'id', $model->id);

        return $next($data);
    }
}
