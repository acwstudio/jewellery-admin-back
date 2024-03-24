<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Product\Relationships;

use Domain\AbstractRelationshipsService;
use Domain\Catalog\Repositories\Product\Relationships\ProductPricesRelationshipsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class ProductPricesRelationshipsService extends AbstractRelationshipsService
{
    public function __construct(protected ProductPricesRelationshipsRepository $repository)
    {
    }

    public function index(array $params): Model|LengthAwarePaginator
    {
        return $this->repository->index($params);
    }

    public function update(array $data): void
    {
        $this->repository->update($data);
    }
}
