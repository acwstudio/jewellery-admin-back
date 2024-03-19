<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Product;

use Domain\Catalog\Repositories\Product\ProductRelationsRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class ProductRelationsService
{
    public function __construct(public ProductRelationsRepository $productRelationsRepository)
    {
    }

    public function indexProductsWeaves(array $data): Paginator
    {
        return $this->productRelationsRepository->indexProductsWeaves($data);
    }

    public function indexProductsPrices(array $data): Paginator
    {
        return $this->productRelationsRepository->indexProductPrices($data);
    }

    public function indexProductsProductCategory(array $data): Model
    {
        return $this->productRelationsRepository->indexProductsProductCategory($data);
    }

    public function indexProductSizes(array $data): Paginator|Model
    {
        return $this->productRelationsRepository->indexProductSizes($data);
    }

    public function indexProductSizeCategories(array $data): Paginator|Model
    {
        return $this->productRelationsRepository->indexProductSizeCategories($data);
    }

    public function indexProductsBlogPosts(array $data): Paginator|Model
    {
        return $this->productRelationsRepository->indexProductsBlogPosts($data);
    }

    public function indexRelations(array $data): Paginator|Model
    {
        return $this->productRelationsRepository->indexRelations($data);
    }



    /**
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        $this->productRelationsRepository->updateRelations($data);
    }

}
