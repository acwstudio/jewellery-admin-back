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

    public function indexRelations(array $data): Paginator|Model
    {
        return $this->priceRelationsRepository->indexRelations($data);
    }

    /**
     * @throws \ReflectionException
     */
    public function updateRelations(array $data): void
    {
        $this->priceRelationsRepository->updateRelations($data);
    }
}
