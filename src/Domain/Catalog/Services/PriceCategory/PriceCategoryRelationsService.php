<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\PriceCategory;

use Domain\Catalog\Repositories\PriceCategory\PriceCategoryRelationsRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class PriceCategoryRelationsService
{
    public function __construct(public PriceCategoryRelationsRepository $priceCategoryRelationsRepository)
    {
    }

    public function indexPriceCategoryPrices(array $data)
    {
        return $this->priceCategoryRelationsRepository->indexPriceCategoryPrices($data);
    }

    public function indexPriceCategorySizes(array $data)
    {
        return $this->priceCategoryRelationsRepository->indexPriceCategorySizes($data);
    }

    public function indexRelations(array $data): Paginator|Model
    {
        return $this->priceCategoryRelationsRepository->indexRelations($data);
    }

    /**
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        $this->priceCategoryRelationsRepository->updateRelations($data);
    }
}
