<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Product\Relationships;

use Domain\AbstractRelationshipsService;
use Domain\Catalog\Repositories\Product\Relationships\ProductSizesRelationshipsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class ProductSizesRelationshipsService extends AbstractRelationshipsService
{
    public function __construct(protected ProductSizesRelationshipsRepository $repository)
    {
    }

    public function index(array $params): LengthAwarePaginator|Model
    {
        return $this->repository->index($params);
    }

    public function update(array $data): void
    {
        $this->repository->update($data);
    }
}
