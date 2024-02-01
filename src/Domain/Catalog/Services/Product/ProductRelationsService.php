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
