<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Product;

use Domain\AbstractCRUDService;
use Domain\Catalog\Pipelines\Product\ProductPipeline;
use Domain\Catalog\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class ProductService extends AbstractCRUDService
{
    public function __construct(
        public ProductRepositoryInterface $productRepositoryInterface,
        public ProductPipeline $productPipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->productRepositoryInterface->index($data);
    }

    public function store(array $data): Model
    {
        return $this->productPipeline->store($data);
    }

    public function show(int $id, array $data): Model
    {
        return $this->productRepositoryInterface->show($id, $data);
    }

    public function update(array $data): void
    {
        $this->productPipeline->update($data);
    }

    public function destroy(int $id): void
    {
        $this->productPipeline->destroy($id);
    }
}
