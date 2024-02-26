<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Product\Pipes;

use Domain\Catalog\Repositories\Product\ProductRepository;

final class ProductStorePipe
{
    public function __construct(public ProductRepository $productRepository)
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
