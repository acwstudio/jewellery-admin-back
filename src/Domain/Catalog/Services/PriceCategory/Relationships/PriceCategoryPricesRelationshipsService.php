<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\PriceCategory\Relationships;

use Domain\Catalog\Repositories\PriceCategory\Relationships\PriceCategoryPricesRelationshipsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class PriceCategoryPricesRelationshipsService
{
    public function __construct(public PriceCategoryPricesRelationshipsRepository $repository)
    {
    }

    public function index(array $params): LengthAwarePaginator
    {
        return $this->repository->index($params);
    }

    public function update(array $params): void
    {
        $this->repository->update($params);
    }
}
