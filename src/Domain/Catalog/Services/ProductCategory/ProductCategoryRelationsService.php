<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\ProductCategory;

use Domain\Catalog\Repositories\ProductCategory\ProductCategoryRelationsRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class ProductCategoryRelationsService
{
    public function __construct(public ProductCategoryRelationsRepository $productCategoryRelationsRepository)
    {
    }

    public function indexRelations(array $data): Paginator|Model
    {
        return $this->productCategoryRelationsRepository->indexRelations($data);
    }

    /**
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        $this->productCategoryRelationsRepository->updateRelations($data);
    }
}
