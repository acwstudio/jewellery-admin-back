<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\ProductCategory\Pipes;

use Domain\Catalog\Repositories\ProductCategory\ProductCategoryRepository;

final class ProductCategoryUpdatePipe
{
    public function __construct(public ProductCategoryRepository $productCategoryRepository)
    {
    }

    public function handle(array $data, \Closure $next): mixed
    {
        $model = $this->productCategoryRepository->update($data);
        data_set($data, 'model', $model);

        return $next($data);
    }
}
