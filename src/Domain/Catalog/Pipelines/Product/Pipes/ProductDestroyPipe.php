<?php

declare(strict_types=1);

namespace Domain\Catalog\Pipelines\Product\Pipes;

use Domain\Catalog\Repositories\Product\ProductRepository;

final class ProductDestroyPipe
{
    public function __construct(public ProductRepository $productRepository)
    {
    }

    public function handle(int $id, \Closure $next): mixed
    {
        $this->productRepository->destroy($id);

        return $next($id);
    }
}
