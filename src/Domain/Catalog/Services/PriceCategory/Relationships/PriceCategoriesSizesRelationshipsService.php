<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\PriceCategory\Relationships;

use Domain\Catalog\Repositories\PriceCategory\Relationships\PriceCategoriesSizesRelationshipsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class PriceCategoriesSizesRelationshipsService
{
    public function __construct(public PriceCategoriesSizesRelationshipsRepository $repository)
    {
    }

    public function index(array $params): LengthAwarePaginator
    {
        return $this->repository->index($params);
    }

    public function update(array $data): void
    {
        $this->repository->update($data);
    }
}
