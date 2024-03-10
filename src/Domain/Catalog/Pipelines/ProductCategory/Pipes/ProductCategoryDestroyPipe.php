<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\ProductCategory\Pipes;

use Domain\Catalog\Repositories\ProductCategory\ProductCategoryRepository;

final class ProductCategoryDestroyPipe
{
    public function __construct(public ProductCategoryRepository $productCategoryRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->productCategoryRepository->destroy($id);

        return $next($id);
    }
}
