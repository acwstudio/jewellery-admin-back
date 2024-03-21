<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\SizeCategory;

use Domain\AbstractRelationsRepository;
use Domain\Catalog\Repositories\SizeCategory\SizeCategoryRelationsRepository;
use Illuminate\Contracts\Pagination\Paginator;

final class SizeCategoryRelationsService extends AbstractRelationsRepository
{
    public function __construct(public SizeCategoryRelationsRepository $sizeCategoryRelationsRepository)
    {
    }

    public function indexSizeCategoryPrices(array $data): Paginator
    {
        return $this->sizeCategoryRelationsRepository->indexSizeCategoryPrices($data);
    }

    public function indexSizeCategorySizes(array $data): Paginator
    {
        return $this->sizeCategoryRelationsRepository->indexSizeCategorySizes($data);
    }

    public function indexSizeCategoriesProducts(array $data): Paginator
    {
        return $this->sizeCategoryRelationsRepository->indexSizeCategoriesProducts($data);
    }

    public function updateRelations(array $data): void
    {
        // TODO: Implement updateRelations() method.
    }
}
