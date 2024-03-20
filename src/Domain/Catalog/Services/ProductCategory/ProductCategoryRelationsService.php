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

    public function indexProductCategoryProducts(array $data): Paginator
    {
        return $this->productCategoryRelationsRepository->indexProductCategoryProducts($data);
    }

    public function indexProductCategoryChildren(array $data): Paginator
    {
        return $this->productCategoryRelationsRepository->indexProductCategoryChildren($data);
    }

    public function indexProductCategoriesParent(array $data): Model
    {
        return $this->productCategoryRelationsRepository->indexProductCategoriesParent($data);
    }

    /**
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        $this->productCategoryRelationsRepository->updateRelations($data);
    }
}
