<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\ProductCategory;

use Domain\AbstractCRUDService;
use Domain\Catalog\Pipelines\ProductCategory\ProductCategoryPipeline;
use Domain\Catalog\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class ProductCategoryService extends AbstractCRUDService
{
    public function __construct(
        public ProductCategoryRepositoryInterface $productCategoryRepositoryInterface,
        public ProductCategoryPipeline $productCategoryPipeline
    ) {
    }

    public function index(array $data): Paginator
    {
        return $this->productCategoryRepositoryInterface->index($data);
    }

    public function store(array $data): Model
    {
        return $this->productCategoryPipeline->store($data);
    }

    public function show(int $id, array $data): Model
    {
        return $this->productCategoryRepositoryInterface->show($id, $data);
    }

    public function update(array $data): void
    {
        $this->productCategoryPipeline->update($data);
    }

    public function destroy(int $id): void
    {
        $this->productCategoryPipeline->destroy($id);
    }
}
