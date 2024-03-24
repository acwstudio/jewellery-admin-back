<?php

declare(strict_types=1);

namespace Domain\Catalog\Services\Product\Relationships;

use Domain\AbstractRelationshipsService;
use Domain\Catalog\Repositories\Product\Relationships\ProductsProductCategoryRelationshipsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class ProductsProductCategoryRelationshipsService  extends AbstractRelationshipsService
{
    public function __construct(protected ProductsProductCategoryRelationshipsRepository $repository)
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
