<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Price;

use Domain\Catalog\Repositories\Price\PriceRelationsRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;

final class PriceRelationsService
{
    public function __construct(public PriceRelationsRepository $priceRelationsRepository)
    {
    }

    public function indexPricesProduct(array $data): Model
    {
        return $this->priceRelationsRepository->indexPricesProduct($data);
    }

    public function indexPricesSize(array $data): Model
    {
        return $this->priceRelationsRepository->indexPricesSize($data);
    }

    public function indexPricesPriceCategory(array $data): Model
    {
        return $this->priceRelationsRepository->indexPricesPriceCategory($data);
    }

    /**
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        $this->priceRelationsRepository->updateRelations($data);
    }
}
