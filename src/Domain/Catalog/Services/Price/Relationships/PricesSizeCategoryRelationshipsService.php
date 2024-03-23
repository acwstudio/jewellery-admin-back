<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Price\Relationships;

use Domain\Catalog\Repositories\Price\Relationships\PricesSizeCategoryRelationshipsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class PricesSizeCategoryRelationshipsService
{
    public function __construct(protected PricesSizeCategoryRelationshipsRepository $repository)
    {
    }

    public function index(array $params): Model
    {
        return $this->repository->index($params);
    }

    public function update(array $data): void
    {
        $this->repository->update($data);
    }
}
