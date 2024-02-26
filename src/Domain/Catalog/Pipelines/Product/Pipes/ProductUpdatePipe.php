<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Product\Pipes;

use Domain\Catalog\Repositories\Product\ProductRepository;

final class ProductUpdatePipe
{
    public function __construct(public ProductRepository $productRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->productRepository->update($data);
        data_set($data, 'model', $model);

        return $next($data);
    }
}
